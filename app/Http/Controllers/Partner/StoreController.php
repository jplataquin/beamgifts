<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    /**
     * Display the partner's store.
     */
    public function show()
    {
        $store = Auth::guard('partner')->user()->store()->withCount(['branches', 'products'])->first();
        
        if (!$store) {
            return redirect()->route('partner.dashboard')->with('error', 'Store not found. Please contact admin.');
        }

        return view('partner.stores.show', compact('store'));
    }

    /**
     * Show the form for editing the store.
     */
    public function edit()
    {
        $store = Auth::guard('partner')->user()->store;
        
        if (!$store) {
            abort(404);
        }

        return view('partner.stores.edit', compact('store'));
    }

    /**
     * Update the store in storage.
     */
    public function update(Request $request)
    {
        $store = Auth::guard('partner')->user()->store;
        
        if (!$store) {
            abort(404);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|string', // Logo path from chunked upload
        ]);

        $store->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        if (!empty($validated['logo'])) {
            $store->update(['logo' => $validated['logo']]);
        }

        return redirect()->route('partner.store.show')->with('success', 'Store updated successfully.');
    }

    /**
     * Handle chunked file upload for store logo
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
        Storage::disk('local')->putFileAs($tempPath, $file, $chunkName);

        // Check if all chunks are uploaded
        $files = Storage::disk('local')->files($tempPath);

        if (count($files) >= $totalChunks) {
            // Use an atomic lock to ensure only one process handles the merge
            $lock = Cache::lock("merge-{$uuid}", 60);

            if ($lock->get()) {
                try {
                    $finalName = Str::random(40) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
                    $finalPath = "logos/{$finalName}";

                    if (!Storage::disk('public')->exists('logos')) {
                        Storage::disk('public')->makeDirectory('logos');
                    }

                    $fullFinalPath = Storage::disk('public')->path($finalPath);
                    $out = fopen($fullFinalPath, "wb");
                    for ($i = 0; $i < $totalChunks; $i++) {
                        $chunkRelativePath = "{$tempPath}/{$i}.part";
                        if (!Storage::disk('local')->exists($chunkRelativePath)) {
                            throw new \Exception("Missing chunk {$i} during merge.");
                        }
                        $chunkFullPath = Storage::disk('local')->path($chunkRelativePath);
                        $in = fopen($chunkFullPath, "rb");
                        while ($buff = fread($in, 4096)) {
                            fwrite($out, $buff);
                        }
                        fclose($in);
                    }
                    fclose($out);

                    Storage::disk('local')->deleteDirectory($tempPath);

                    return response()->json([
                        'completed' => true,
                        'path' => $finalPath,
                        'name' => $finalName
                    ]);
                } catch (\Exception $e) {
                    Log::error("Store logo merge failed for {$uuid}: " . $e->getMessage());
                    return response()->json(['error' => 'Merge failed: ' . $e->getMessage()], 500);
                } finally {
                    $lock->release();
                }
            } else {
                return response()->json(['completed' => true, 'status' => 'merging']);
            }
        }

        return response()->json(['completed' => false]);
    }
}
