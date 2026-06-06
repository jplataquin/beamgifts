<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('store');

        if ($request->get('status') === 'pending') {
            $query->where('is_approved', false);
        }

        if ($request->filled('store_id')) {
            $query->where('store_id', $request->store_id);
        }

        $products = $query->latest()->paginate(10);
        $stores = \App\Models\Store::orderBy('name')->get();
        
        return view('admin.products.index', compact('products', 'stores'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
            'images' => 'nullable|array',
            'images.*' => 'string',
        ]);

        $product->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'images' => $validated['images'] ?? [],
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Handle chunked file upload
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
                    $finalPath = "products/{$finalName}";

                    if (!Storage::disk('public')->exists('products')) {
                        Storage::disk('public')->makeDirectory('products');
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
                    Log::error("Product merge failed for {$uuid}: " . $e->getMessage());
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

    public function toggleBan(Product $product)
    {
        $product->update(['is_banned' => !$product->is_banned]);
        $status = $product->is_banned ? 'banned' : 'unbanned';
        return back()->with('success', "Product has been {$status}.");
    }

    public function toggleApproval(Product $product)
    {
        $product->update(['is_approved' => !$product->is_approved]);
        $status = $product->is_approved ? 'approved' : 'unapproved';
        return back()->with('success', "Product has been {$status}.");
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
