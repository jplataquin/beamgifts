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
        Partner::create([
            'name' => 'John Partner',
            'email' => 'partner@example.com',
            'business_name' => 'John\'s Delights',
            'password' => Hash::make('password'),
        ]);

        Partner::create([
            'name' => 'Sarah Store',
            'email' => 'sarah@example.com',
            'business_name' => 'Sarah\'s Boutique',
            'password' => Hash::make('password'),
        ]);
    }
}
