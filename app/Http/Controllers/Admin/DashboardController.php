<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partner;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $partnerCount = Partner::count();
        $productCount = Product::count();
        return view('admin.dashboard', compact('partnerCount', 'productCount'));
    }
}
