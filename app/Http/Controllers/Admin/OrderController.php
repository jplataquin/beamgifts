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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // If the search looks like an ID, search by ID
                if (is_numeric($search)) {
                    $q->where('id', $search);
                }
                $q->orWhere('hitpay_transaction_id', 'like', '%' . $search . '%');
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        if ($request->ajax()) {
            return response(view('admin.orders._rows', compact('orders'))->render())
                ->header('X-Has-More-Pages', $orders->hasMorePages() ? '1' : '0');
        }

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order details.
     */
    public function show(Order $order)
    {
        $order->load(['gifter', 'items.product.store', 'vouchers']);
        return view('admin.orders.show', compact('order'));
    }
}
