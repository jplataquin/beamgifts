<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Product;
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
        
        return view('partner.dashboard', compact('partner', 'store', 'productCount', 'branchCount'));
    }
}
