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

    public function partnerIntro()
    {
        return view('pages.partner-intro');
    }

    public function partnerApply(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:255',
        ]);

        return back()->with('success', 'Thank you for your interest! We will contact you soon.');
    }
}
