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
    public function index(Request $request)
    {
        $partner = Auth::guard('partner')->user();
        $store = $partner->store;
        
        if (!$store) {
            return redirect()->route('partner.dashboard')->with('error', 'Store not found. Please contact admin.');
        }

        $storeId = $store->id;

        $query = Voucher::whereIn('product_id', function($q) use ($storeId) {
            $q->select('id')->from('products')->where('store_id', $storeId);
        })->with(['product.store', 'order.gifter', 'claimedByUser', 'claimedBranch']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('branch_id')) {
            $query->where('claimed_branch_id', $request->branch_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $vouchers = $query->latest()->paginate(15)->withQueryString();
        $branches = $store->branches;

        return view('partner.vouchers.index', compact('vouchers', 'branches'));
    }

    /**
     * Print vouchers list based on current filters.
     */
    public function print(Request $request)
    {
        $partner = Auth::guard('partner')->user();
        $store = $partner->store;

        if (!$store) {
            return redirect()->route('partner.dashboard')->with('error', 'Store not found.');
        }

        $storeId = $store->id;

        $query = Voucher::whereIn('product_id', function($q) use ($storeId) {
            $q->select('id')->from('products')->where('store_id', $storeId);
        })->with(['product.store', 'order.gifter', 'claimedByUser', 'claimedBranch']);

        $filters = [];

        if ($request->filled('status')) {
            $query->where('status', $request->status);
            $filters['Status'] = ucfirst($request->status);
        }

        if ($request->filled('branch_id')) {
            $query->where('claimed_branch_id', $request->branch_id);
            $branch = $store->branches->where('id', $request->branch_id)->first();
            if ($branch) {
                $filters['Branch'] = $branch->name;
            }
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
            $filters['From Date'] = \Carbon\Carbon::parse($request->from_date)->format('M d, Y');
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
            $filters['To Date'] = \Carbon\Carbon::parse($request->to_date)->format('M d, Y');
        }

        $vouchers = $query->latest()->get();
        
        $grandTotal = $vouchers->sum(function($voucher) {
            return $voucher->price ?? $voucher->product->price;
        });

        return view('partner.vouchers.print', compact('vouchers', 'filters', 'grandTotal', 'store'));
    }

    /**
     * Show the voucher details.
     */
    public function show(Voucher $voucher)
    {
        $partner = Auth::guard('partner')->user();
        if (!$partner->store) {
            return redirect()->route('partner.dashboard')->with('error', 'Store not found.');
        }
        $storeId = $partner->store->id;

        // Security check: Ensure voucher belongs to this partner
        if ($voucher->product->store_id !== $storeId) {
            abort(403);
        }

        return view('partner.vouchers.show', compact('voucher'));
    }
}
