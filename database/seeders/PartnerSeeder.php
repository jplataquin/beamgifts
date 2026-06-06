<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Partner;
use Illuminate\Support\Facades\Hash;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $s1 = \App\Models\Store::create([
            'name' => 'John\'s Delights',
            'slug' => 'johns-delights',
            'description' => 'Fine dining and more.',
        ]);

        Partner::create([
            'name' => 'John Partner',
            'email' => 'partner@example.com',
            'business_name' => 'John\'s Delights',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'store_id' => $s1->id,
        ]);

        $s2 = \App\Models\Store::create([
            'name' => 'Sarah\'s Boutique',
            'slug' => 'sarahs-boutique',
            'description' => 'Trendy fashion for everyone.',
        ]);

        Partner::create([
            'name' => 'Sarah Store',
            'email' => 'sarah@example.com',
            'business_name' => 'Sarah\'s Boutique',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'store_id' => $s2->id,
        ]);
    }
}
