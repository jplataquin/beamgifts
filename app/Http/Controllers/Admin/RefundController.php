<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function index()
    {
        $refunds = Voucher::whereIn('status', ['refund_pending', 'refunded'])
            ->with(['product.store', 'order.gifter'])
            ->latest('refund_requested_at')
            ->paginate(15);

        return view('admin.refunds.index', compact('refunds'));
    }

    public function approve(Voucher $voucher)
    {
        if ($voucher->status !== 'refund_pending') {
            return back()->with('error', 'Only pending refunds can be approved.');
        }

        $voucher->update([
            'status' => 'refunded',
            'refunded_at' => now(),
        ]);

        return back()->with('success', 'Refund approved successfully.');
    }

    public function reject(Voucher $voucher)
    {
        if ($voucher->status !== 'refund_pending') {
            return back()->with('error', 'Only pending refunds can be rejected.');
        }

        $voucher->update([
            'status' => 'active',
            'refund_requested_at' => null,
            'refund_reason' => null,
        ]);

        return back()->with('success', 'Refund request rejected. Voucher is now active again.');
    }
}
