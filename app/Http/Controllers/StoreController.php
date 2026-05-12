<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;

class StoreController extends Controller
{
    public function show($city_slug, $store_slug)
    {
        $city = app('current_city');
        $store = Store::where('slug', $store_slug)
            ->whereHas('branches', function($query) use ($city) {
                $query->where('city_id', $city->id);
            })->with(['products' => function($query) {
                $query->active();
            }, 'branches' => function($query) use ($city) {
                $query->where('city_id', $city->id);
            }])->firstOrFail();

        return view('store.show', compact('store', 'city'));
    }
}
