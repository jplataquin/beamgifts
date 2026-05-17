<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of all platform orders.
     */
    public function index(Request $request)
    {
        $query = Order::with(['gifter', 'items.product.store']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('reference_number')) {
            $query->where('reference_number', 'like', '%' . $request->reference_number . '%');
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order details.
     */
    public function show(Order $order)
    {
        $order->load(['gifter', 'items.product.store', 'items.voucher']);
        return view('admin.orders.show', compact('order'));
    }
}
