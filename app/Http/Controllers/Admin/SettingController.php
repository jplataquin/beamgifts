<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $markup = Setting::get('global_markup_percentage', 0);
        $about = Setting::get('page_about', '');
        $terms = Setting::get('page_terms', '');
        $privacy = Setting::get('page_privacy', '');

        return view('admin.settings.index', compact('markup', 'about', 'terms', 'privacy'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'global_markup_percentage' => 'required|numeric|min:0|max:100',
            'page_about' => 'nullable|string',
            'page_terms' => 'nullable|string',
            'page_privacy' => 'nullable|string',
        ]);

        Setting::set('global_markup_percentage', $request->global_markup_percentage);
        Setting::set('page_about', $request->page_about);
        Setting::set('page_terms', $request->page_terms);
        Setting::set('page_privacy', $request->page_privacy);

        return back()->with('success', 'Settings and pages updated successfully.');
    }
}
