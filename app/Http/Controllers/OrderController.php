<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the gifter's orders.
     */
    public function index()
    {
        $orders = Order::where('gifter_id', Auth::id())
            ->with(['items'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        if ($order->gifter_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items', 'vouchers.product.store']);
        
        return view('orders.show', compact('order'));
    }
}
