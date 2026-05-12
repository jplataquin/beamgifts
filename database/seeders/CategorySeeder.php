<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Food & Drinks', 'slug' => 'food-drinks'],
            ['name' => 'Flowers', 'slug' => 'flowers'],
            ['name' => 'Experiences', 'slug' => 'experiences'],
            ['name' => 'Fashion', 'slug' => 'fashion'],
            ['name' => 'Wellness', 'slug' => 'wellness'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
