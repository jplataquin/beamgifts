<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $content = Setting::get('page_about', 'About Beam Gifts content coming soon.');
        return view('pages.show', ['title' => 'About Us', 'content' => $content]);
    }

    public function terms()
    {
        $content = Setting::get('page_terms', 'Terms of Service content coming soon.');
        return view('pages.show', ['title' => 'Terms of Service', 'content' => $content]);
    }

    public function privacy()
    {
        $content = Setting::get('page_privacy', 'Privacy Policy content coming soon.');
        return view('pages.show', ['title' => 'Privacy Policy', 'content' => $content]);
    }
}
