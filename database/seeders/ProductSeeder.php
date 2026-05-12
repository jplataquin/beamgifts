<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = Store::all();
        $categories = Category::all();

        $sampleData = [
            'food-drinks' => [
                ['name' => 'Premium Gift Basket', 'price' => 2500.00, 'desc' => 'A curated selection of artisanal snacks and drinks.'],
                ['name' => 'Wine & Cheese Pairing', 'price' => 4500.00, 'desc' => 'Classic red wine paired with three types of local cheese.'],
            ],
            'flowers' => [
                ['name' => 'Sunlight Bouquet', 'price' => 1500.00, 'desc' => 'Bright yellow sunflowers to light up any room.'],
                ['name' => 'Pure White Lilies', 'price' => 2800.00, 'desc' => 'Elegant and fragrant white lilies.'],
            ],
            'experiences' => [
                ['name' => 'Weekend Staycation', 'price' => 7500.00, 'desc' => 'A relaxing night for two in a premium suite.'],
                ['name' => 'Guided City Tour', 'price' => 2000.00, 'desc' => 'Explore the hidden gems of the city with a professional guide.'],
            ],
            'fashion' => [
                ['name' => 'Leather Travel Wallet', 'price' => 1800.00, 'desc' => 'Handcrafted genuine leather wallet for the frequent traveler.'],
                ['name' => 'Silk Evening Scarf', 'price' => 1200.00, 'desc' => 'Soft, lightweight silk scarf with a minimalist pattern.'],
            ],
            'wellness' => [
                ['name' => 'Zen Meditation Kit', 'price' => 2500.00, 'desc' => 'Includes a cushion, incense burner, and guide.'],
                ['name' => 'Luxury Bath Set', 'price' => 1400.00, 'desc' => 'Organic bath bombs, salts, and a plush robe.'],
            ],
        ];

        Schema::disableForeignKeyConstraints();
        Product::truncate();
        Schema::enableForeignKeyConstraints();

        foreach ($stores as $store) {
            foreach ($categories as $category) {
                // Get samples for this category slug, or fallback to first one if not found
                $items = $sampleData[$category->slug] ?? $sampleData['food-drinks'];

                foreach ($items as $item) {
                    Product::create([
                        'store_id' => $store->id,
                        'category_id' => $category->id,
                        'category' => $category->name,
                        'name' => $store->name . ' - ' . $item['name'],
                        'slug' => Str::slug($store->name . '-' . $item['name'] . '-' . rand(100, 999)),
                        'description' => $item['desc'],
                        'price' => $item['price'],
                        'images' => [],
                    ]);
                }
            }
        }
    }
}
