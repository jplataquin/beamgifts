<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function up(): void
    {
        City::create(['name' => 'Manila', 'slug' => 'manila']);
        City::create(['name' => 'Cebu', 'slug' => 'cebu']);
        City::create(['name' => 'Davao', 'slug' => 'davao']);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->up();
    }
}
