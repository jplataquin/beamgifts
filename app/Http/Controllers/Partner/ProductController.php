<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $partner = Auth::guard('partner')->user();
        $products = Product::where('store_id', $partner->store->id)
            ->with('store')
            ->latest()
            ->paginate(10);

        return view('partner.products.index', compact('products'));
    }

    public function create()
    {
        $store = Auth::guard('partner')->user()->store;
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('partner.products.create', compact('store', 'categories'));
    }

    public function store(Request $request)
    {
        $store = Auth::guard('partner')->user()->store;

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'images' => 'nullable|array',
            'images.*' => 'string', // Filenames from chunked upload
        ]);

        $category = \App\Models\Category::find($validated['category_id']);

        Product::create([
            'store_id' => $store->id,
            'category_id' => $validated['category_id'],
            'category' => $category->name,
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . rand(1000, 9999),
            'description' => $validated['description'],
            'price' => $validated['price'],
            'images' => $validated['images'] ?? [],
        ]);

        return redirect()->route('partner.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $this->authorizeProduct($product);
        $store = Auth::guard('partner')->user()->store;
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('partner.products.edit', compact('product', 'store', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'images' => 'nullable|array',
        ]);

        $category = \App\Models\Category::find($validated['category_id']);
        $validated['category'] = $category->name;

        $product->update($validated);

        return redirect()->route('partner.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);
        $product->delete();
        return redirect()->route('partner.products.index')->with('success', 'Product deleted successfully.');
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

    protected function authorizeProduct(Product $product)
    {
        if ($product->store->partner_id !== Auth::guard('partner')->id()) {
            abort(403);
        }
    }
}
