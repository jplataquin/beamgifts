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
    public function index(Request $request)
    {
        $partners = \App\Models\Partner::where('role', 'owner')->orderBy('business_name')->get();

        // 1. Eligible Vouchers Query
        $eligibleQuery = Voucher::where('status', 'claimed')
            ->where('payout_flag', false)
            ->whereNull('payout_id')
            ->with(['product.store', 'order.gifter']);

        // 2. Flagged Vouchers Query
        $flaggedQuery = Voucher::where('payout_flag', true)
            ->whereNull('payout_id')
            ->with(['product.store.owner']);

        // Apply Filters
        if ($request->filled('partner_id')) {
            $partnerId = $request->partner_id;
            $partner = \App\Models\Partner::find($partnerId);
            
            if ($partner && $partner->store_id) {
                $storeId = $partner->store_id;
                $eligibleQuery->whereHas('product', function($q) use ($storeId) {
                    $q->where('store_id', $storeId);
                });
                $flaggedQuery->whereHas('product', function($q) use ($storeId) {
                    $q->where('store_id', $storeId);
                });
            } else {
                // If partner doesn't exist or has no store, return empty results
                $eligibleQuery->whereRaw('1 = 0');
                $flaggedQuery->whereRaw('1 = 0');
            }
        }

        if ($request->filled('from_date')) {
            $eligibleQuery->whereDate('claimed_at', '>=', $request->from_date);
            $flaggedQuery->whereDate('claimed_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $eligibleQuery->whereDate('claimed_at', '<=', $request->to_date);
            $flaggedQuery->whereDate('claimed_at', '<=', $request->to_date);
        }

        $eligibleVouchers = $eligibleQuery->latest('claimed_at')->get();

        $flaggedVouchers = $flaggedQuery->get()
            ->groupBy(function($voucher) {
                return $voucher->product->store->owner->id ?? 0;
            });

        return view('admin.payouts.index', compact('eligibleVouchers', 'flaggedVouchers', 'partners'));
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
