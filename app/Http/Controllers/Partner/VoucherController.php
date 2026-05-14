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
        $storeId = $partner->store->id;

        $query = Voucher::whereIn('product_id', function($q) use ($storeId) {
            $q->select('id')->from('products')->where('store_id', $storeId);
        })->with(['product.store', 'order.gifter', 'claimedByUser', 'claimedBranch']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $vouchers = $query->latest()->paginate(15)->withQueryString();

        return view('partner.vouchers.index', compact('vouchers'));
    }

    /**
     * Show the voucher details.
     */
    public function show(Voucher $voucher)
    {
        $partner = Auth::guard('partner')->user();
        $storeId = $partner->store->id;

        // Security check: Ensure voucher belongs to this partner
        if ($voucher->product->store_id !== $storeId) {
            abort(403);
        }

        return view('partner.vouchers.show', compact('voucher'));
    }
}
