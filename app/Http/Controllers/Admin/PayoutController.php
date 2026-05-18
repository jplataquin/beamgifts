<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayoutController extends Controller
{
    /**
     * Display the payouts management dashboard.
     */
    public function index()
    {
        // Vouchers claimed but not yet tagged for payout
        $eligibleVouchers = Voucher::where('status', 'claimed')
            ->where('payout_flag', false)
            ->whereNull('payout_id')
            ->with(['product.store', 'order.gifter'])
            ->latest('claimed_at')
            ->get();

        // Vouchers tagged for payout but no payout record created yet
        $flaggedVouchers = Voucher::where('payout_flag', true)
            ->whereNull('payout_id')
            ->with(['product.store'])
            ->get()
            ->groupBy(function($voucher) {
                return $voucher->product->store->partner_id;
            });

        return view('admin.payouts.index', compact('eligibleVouchers', 'flaggedVouchers'));
    }

    /**
     * Bulk tag vouchers for payout.
     */
    public function tag(Request $request)
    {
        $request->validate([
            'voucher_ids' => 'required|array',
            'voucher_ids.*' => 'exists:vouchers,id',
        ]);

        Voucher::whereIn('id', $request->voucher_ids)
            ->update(['payout_flag' => true]);

        return back()->with('success', count($request->voucher_ids) . ' vouchers have been flagged for payout.');
    }
}
