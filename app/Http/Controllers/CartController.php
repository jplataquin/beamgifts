<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $city = app('current_city');
        $cart = Session::get("cart_{$city->id}", []);
        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total', 'city'));
    }

    public function add(Request $request, $city_slug, Product $product)
    {
        $city = app('current_city');
        
        // Verify product belongs to a store in this city
        $hasBranchInCity = $product->store->branches()->where('city_id', $city->id)->exists();
        if (!$hasBranchInCity) {
            return back()->with('error', 'This product is not available in ' . $city->name);
        }

        $cartKey = "cart_{$city->id}";
        $cart = Session::get($cartKey, []);

        if (isset($cart[$product->id])) {
            if ($cart[$product->id]['quantity'] >= 3) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Maximum quantity reached (3 per item).'
                    ], 422);
                }
                return back()->with('error', 'Maximum quantity reached (3 per item).');
            }
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => !empty($product->images) ? $product->images[0] : null,
                'store_name' => $product->store->name,
            ];
        }

        Session::put($cartKey, $cart);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'cartCount' => count($cart),
                'message' => 'Product added to cart.'
            ]);
        }

        return redirect()->route('cart.index', ['city_slug' => $city->slug])->with('success', 'Product added to cart.');
    }

    public function remove($city_slug, $productId)
    {
        $city = app('current_city');
        $cartKey = "cart_{$city->id}";
        $cart = Session::get($cartKey, []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put($cartKey, $cart);
        }

        return back()->with('success', 'Product removed from cart.');
    }

    public function update(Request $request, $city_slug, $productId)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:3']);
        
        $city = app('current_city');
        $cartKey = "cart_{$city->id}";
        $cart = Session::get($cartKey, []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $request->quantity;
            Session::put($cartKey, $cart);
        }

        if ($request->wantsJson() || $request->ajax()) {
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
            
            $markupPercent = \App\Models\Setting::get('global_markup_percentage', 0);
            $markupAmount = ($total * $markupPercent) / 100;
            $grandTotal = $total + $markupAmount;

            return response()->json([
                'success' => true,
                'message' => 'Cart updated.',
                'itemSubtotal' => number_format($cart[$productId]['price'] * $cart[$productId]['quantity'], 2),
                'total' => number_format($total, 2),
                'markupAmount' => number_format($markupAmount, 2),
                'grandTotal' => number_format($grandTotal, 2),
            ]);
        }

        return back()->with('success', 'Cart updated.');
    }
}
