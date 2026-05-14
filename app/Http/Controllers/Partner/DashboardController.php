<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $partner = Auth::guard('partner')->user();
        $store = $partner->store;
        
        $productCount = $store ? $store->products()->count() : 0;
        $branchCount = $store ? $store->branches()->count() : 0;
        
        $unclaimedVoucherCount = 0;
        if ($store) {
            $unclaimedVoucherCount = Voucher::where('status', 'active')
                ->whereIn('product_id', $store->products()->pluck('id'))
                ->count();
        }
        
        return view('partner.dashboard', compact('partner', 'store', 'productCount', 'branchCount', 'unclaimedVoucherCount'));
    }
}
