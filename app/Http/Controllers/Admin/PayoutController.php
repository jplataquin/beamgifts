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

        // Apply Filters
        if ($request->filled('partner_id')) {
            $partnerId = $request->partner_id;
            $partner = \App\Models\Partner::find($partnerId);
            
            if ($partner && $partner->store_id) {
                $storeId = $partner->store_id;
                $eligibleQuery->whereHas('product', function($q) use ($storeId) {
                    $q->where('store_id', $storeId);
                });
            } else {
                $eligibleQuery->whereRaw('1 = 0');
            }
        }

        if ($request->filled('from_date')) {
            $eligibleQuery->whereDate('claimed_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $eligibleQuery->whereDate('claimed_at', '<=', $request->to_date);
        }

        $eligibleVouchers = $eligibleQuery->latest('claimed_at')->get();

        // 2. Fetch Payout History
        $payouts = Payout::with('partner')->latest()->get();

        return view('admin.payouts.index', compact('eligibleVouchers', 'payouts', 'partners'));
    }

    /**
     * Create a payout record and associate vouchers.
     */
    public function store(Request $request)
    {
        $request->validate([
            'voucher_ids' => 'required|array',
            'voucher_ids.*' => 'exists:vouchers,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
        ]);

        try {
            // Fetch vouchers with their store/owner relation
            $vouchers = Voucher::whereIn('id', $request->voucher_ids)
                ->with('product.store.owner')
                ->get();

            // 1. Revalidate Status: Ensure all vouchers are 'claimed' and not already paid
            foreach ($vouchers as $voucher) {
                if ($voucher->status !== 'claimed' || $voucher->payout_id !== null) {
                    return back()->with('error', "Voucher #{$voucher->id} is not eligible for payout (Must be 'claimed' and not yet processed).");
                }
            }

            // 2. Revalidate Partner: Ensure all vouchers belong to the same partner
            $partnerIds = $vouchers->map(fn($v) => $v->product->store->owner->id ?? null)->unique();

            if ($partnerIds->count() > 1) {
                return back()->with('error', 'Validation failed: Selected vouchers belong to multiple partners. Please select vouchers from a single partner only.');
            }

            $partnerId = $partnerIds->first();
            if (!$partnerId) {
                return back()->with('error', 'Validation failed: Could not identify the partner for selected vouchers.');
            }

            // 3. Determine Date Range
            $fromDate = $request->from_date ?: $vouchers->min('claimed_at')->format('Y-m-d');
            $toDate = $request->to_date ?: $vouchers->max('claimed_at')->format('Y-m-d');

            DB::beginTransaction();

            // 4. Create Payout record
            $payout = Payout::create([
                'partner_id' => $partnerId,
                'from' => $fromDate,
                'to' => $toDate,
                'status' => 'pending',
            ]);

            // 5. Update Vouchers
            Voucher::whereIn('id', $request->voucher_ids)
                ->update([
                    'payout_id' => $payout->id,
                    'payout_flag' => true,
                    'processed_at' => now(),
                ]);

            DB::commit();

            return back()->with('success', 'Payout ID #' . str_pad($payout->id, 6, '0', STR_PAD_LEFT) . ' has been generated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to generate payout: ' . $e->getMessage());
        }
    }
}
