<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    /**
     * List all vouchers belonging to the partner's store.
     */
    public function index()
    {
        $partner = Auth::guard('partner')->user();
        $storeId = $partner->store->id;

        $vouchers = Voucher::whereIn('product_id', function($q) use ($storeId) {
            $q->select('id')->from('products')->where('store_id', $storeId);
        })->with(['product.store', 'order.gifter'])->latest()->paginate(15);

        return view('partner.vouchers.index', compact('vouchers'));
    }

    /**
     * Show the scanning interface.
     */
    public function scan()
    {
        return view('partner.vouchers.scan');
    }

    /**
     * Process the scanned QR code token.
     */
    public function scanResult($token)
    {
        $partner = Auth::guard('partner')->user();
        $storeId = $partner->store->id;

        $voucher = Voucher::where('unique_token', $token)->with(['product.store', 'order.gifter'])->firstOrFail();

        // Security check: Ensure voucher belongs to this partner
        if ($voucher->product->store_id !== $storeId) {
            return view('partner.vouchers.scan_result', [
                'error' => 'This voucher belongs to a different partner/store.',
                'voucher' => $voucher
            ]);
        }

        return view('partner.vouchers.scan_result', compact('voucher'));
    }

    /**
     * Mark the voucher as claimed.
     */
    public function claim(Request $request, Voucher $voucher)
    {
        $partner = Auth::guard('partner')->user();
        $storeId = $partner->store->id;

        if ($voucher->product->store_id !== $storeId) {
            abort(403);
        }

        if ($voucher->status !== 'active') {
            return back()->with('error', 'Only active vouchers can be claimed.');
        }

        if ($voucher->expires_at && $voucher->expires_at->isPast()) {
            $voucher->update(['status' => 'expired']);
            return back()->with('error', 'This voucher has expired.');
        }

        $voucher->update([
            'status' => 'claimed',
            'claimed_at' => now(),
        ]);

        return redirect()->route('partner.vouchers.index')->with('success', 'Voucher claimed successfully!');
    }
}
