<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;
use App\Models\Category;

class CityController extends Controller
{
    public function showProduct($city_slug, $product_slug)
    {
        $city = app('current_city');
        $product = Product::active()
            ->where('slug', $product_slug)
            ->whereHas('store.branches', function($query) use ($city) {
                $query->where('city_id', $city->id);
            })->with(['store.branches' => function($q) use ($city) {
                $q->where('city_id', $city->id);
            }, 'category_rel'])->firstOrFail();

        return view('product.show', compact('city', 'product'));
    }

    public function index(Request $request, $city_slug)
    {
        $city = app('current_city');
        
        $query = Product::active()->whereHas('store.branches', function($query) use ($city) {
            $query->where('city_id', $city->id);
        })->with(['store', 'category_rel']);

        // Search by keyword (name or description)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Filter by Category
        if ($request->filled('category')) {
            $query->whereHas('category_rel', function($sub) use ($request) {
                $sub->where('slug', $request->category);
            });
        }

        // Filter by Store (Brand)
        if ($request->filled('store')) {
            $query->whereHas('store', function($sub) use ($request) {
                $sub->where('slug', $request->store);
            });
        }

        // Group products by category
        $products = $query->latest()->get()->groupBy(function($item) {
            return $item->category_rel ? $item->category_rel->name : 'Uncategorized';
        });

        // Get available categories and stores for filters in this city
        $availableCategories = Category::whereHas('products.store.branches', function($q) use ($city) {
            $q->where('city_id', $city->id);
        })->get();

        $stores = Store::whereHas('branches', function($q) use ($city) {
            $q->where('city_id', $city->id);
        })->get();

        return view('city.index', compact('city', 'products', 'availableCategories', 'stores'));
    }
}
