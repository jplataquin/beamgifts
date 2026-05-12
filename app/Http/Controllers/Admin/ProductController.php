<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('store')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
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
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function toggleBan(Product $product)
    {
        $product->update(['is_banned' => !$product->is_banned]);
        $status = $product->is_banned ? 'banned' : 'unbanned';
        return back()->with('success', "Product has been {$status}.");
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
