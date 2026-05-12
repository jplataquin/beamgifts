<?php

namespace Database\Seeders;

use App\Models\Gifter;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gifter::factory(10)->create();

        Gifter::factory()->create([
            'name' => 'Test Gifter',
            'email' => 'test@example.com',
        ]);
    }
}
