<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class VoucherController extends Controller
{
    /**
     * Public view for the giftee to see their voucher.
     */
    public function show($token)
    {
        $voucher = Voucher::where('unique_token', $token)->with(['product.store.branches'])->firstOrFail();
        
        // Generate QR Code as SVG
        $qrCode = QrCode::size(250)
            ->margin(1)
            ->generate(route('partner.vouchers.scan.result', ['token' => $token]));

        return view('vouchers.show', compact('voucher', 'qrCode'));
    }

    /**
     * Gifter's view of their own vouchers.
     */
    public function index()
    {
        $vouchers = Voucher::whereHas('order', function($q) {
            $q->where('gifter_id', Auth::id());
        })->with(['product.store'])->latest()->paginate(10);

        return view('vouchers.index', compact('vouchers'));
    }

    public function updateMessage(Request $request, Voucher $voucher)
    {
        // Authorization Check
        $ownerId = \Illuminate\Support\Facades\DB::table('orders')
            ->where('id', $voucher->order_id)
            ->value('gifter_id');

        if ($ownerId != Auth::id()) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'personal_message' => 'nullable|string|max:1000',
            'custom_photo' => 'nullable|string' // Path from chunked upload
        ]);

        $data = [
            'personal_message' => $request->personal_message,
        ];

        if ($request->filled('custom_photo')) {
            $data['custom_photo'] = $request->custom_photo;
        }

        $voucher->update($data);

        return back()->with('success', 'Gift personalized successfully.');
    }

    /**
     * Handle chunked file upload for personalization.
     */
    public function uploadChunk(Request $request)
    {
        $file = $request->file('file');
        $fileName = $request->input('fileName');
        $chunkIndex = $request->input('chunkIndex');
        $totalChunks = $request->input('totalChunks');
        $uuid = $request->input('uuid');

        $tempPath = "chunks/{$uuid}";
        $chunkName = "{$chunkIndex}.part";

        // Store chunk
        \Illuminate\Support\Facades\Storage::disk('local')->putFileAs($tempPath, $file, $chunkName);

        // Check if all chunks are uploaded
        $files = \Illuminate\Support\Facades\Storage::disk('local')->files($tempPath);

        if (count($files) >= $totalChunks) {
            // Use an atomic lock to ensure only one process handles the merge
            $lock = Cache::lock("merge-{$uuid}", 60);

            if ($lock->get()) {
                try {
                    $finalName = \Illuminate\Support\Str::random(40) . '.webp';
                    $finalPath = "vouchers/{$finalName}";

                    // Ensure public directory exists
                    if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('vouchers')) {
                        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('vouchers');
                    }

                    // Prepare a temporary file for merging
                    $mergedTempFile = storage_path("app/temp_merge_{$uuid}");
                    $out = fopen($mergedTempFile, "wb");
                    
                    // Double check all chunks exist before merging
                    for ($i = 0; $i < $totalChunks; $i++) {
                        $chunkRelativePath = "{$tempPath}/{$i}.part";
                        
                        if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($chunkRelativePath)) {
                            fclose($out);
                            @unlink($mergedTempFile);
                            throw new \Exception("Missing chunk {$i} during merge.");
                        }

                        $chunkFullPath = \Illuminate\Support\Facades\Storage::disk('local')->path($chunkRelativePath);
                        $in = fopen($chunkFullPath, "rb");
                        while ($buff = fread($in, 4096)) {
                            fwrite($out, $buff);
                        }
                        fclose($in);
                    }
                    fclose($out);

                    // Optimize Image using Intervention Image (v3)
                    $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                    $image = $manager->read($mergedTempFile);
                    
                    // Resize to max 1080px width while maintaining aspect ratio
                    $image->scale(width: 1080);
                    
                    // Encode to WebP (quality 80)
                    $encoded = $image->toWebp(80);
                    
                    // Save to public storage
                    \Illuminate\Support\Facades\Storage::disk('public')->put($finalPath, $encoded);
                    
                    // Cleanup
                    @unlink($mergedTempFile);
                    \Illuminate\Support\Facades\Storage::disk('local')->deleteDirectory($tempPath);

                    return response()->json([
                        'completed' => true,
                        'path' => $finalPath,
                        'name' => $finalName
                    ]);
                } catch (\Exception $e) {
                    Log::error("Merge failed for {$uuid}: " . $e->getMessage());
                    return response()->json(['error' => 'Merge failed: ' . $e->getMessage()], 500);
                } finally {
                    $lock->release();
                }
            } else {
                // Another process is already merging
                return response()->json(['completed' => true, 'status' => 'merging']);
            }
        }

        return response()->json(['completed' => false]);
    }
}
