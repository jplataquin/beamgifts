<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::set('page_about', "Welcome to Beam Gifts!\n\nWe are a platform dedicated to making gifting simple, digital, and local. Our mission is to connect gifters with amazing local stores in their cities, allowing them to send thoughtful digital vouchers instantly.");
        
        Setting::set('page_terms', "Terms of Service\n\n1. Vouchers are valid for 6 months from the date of purchase.\n2. Vouchers must be redeemed at the designated partner store.\n3. Refunds are subject to store policy and admin approval.");
        
        Setting::set('page_privacy', "Privacy Policy\n\nYour privacy is important to us. We only collect the necessary information to process your orders and ensure a secure gifting experience. We do not sell your data to third parties.");
    }
}
