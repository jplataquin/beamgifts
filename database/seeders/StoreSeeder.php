<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Store;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manila = City::where('slug', 'manila')->first();
        $cebu = City::where('slug', 'cebu')->first();
        
        $foodCat = Category::where('slug', 'food-drinks')->first();
        $flowerCat = Category::where('slug', 'flowers')->first();

        // Store 1
        $store1 = Store::create([
            'partner_id' => 1,
            'name' => 'Pastel Bakery',
            'slug' => 'pastel-bakery',
            'description' => 'Sweet treats and custom cakes.',
        ]);

        Branch::create([
            'store_id' => $store1->id,
            'city_id' => $manila->id,
            'name' => 'Pastel Bakery Manila Central',
            'address' => '123 Baker St, Manila',
        ]);

        Product::create([
            'store_id' => $store1->id,
            'category_id' => $foodCat->id,
            'category' => $foodCat->name,
            'name' => 'Assorted Macarons (Box of 12)',
            'slug' => 'assorted-macarons-12',
            'description' => 'A colorful box of our best macarons.',
            'price' => 1200.00,
        ]);

        // Store 2
        $store2 = Store::create([
            'partner_id' => 2,
            'name' => 'Bloom Boutique',
            'slug' => 'bloom-boutique',
            'description' => 'Beautiful flower arrangements for all occasions.',
        ]);

        Branch::create([
            'store_id' => $store2->id,
            'city_id' => $manila->id,
            'name' => 'Bloom Manila',
            'address' => '456 Flower Rd, Manila',
        ]);

        Branch::create([
            'store_id' => $store2->id,
            'city_id' => $cebu->id,
            'name' => 'Bloom Cebu',
            'address' => '789 Island Ave, Cebu',
        ]);

        Product::create([
            'store_id' => $store2->id,
            'category_id' => $flowerCat->id,
            'category' => $flowerCat->name,
            'name' => 'Spring Pastel Bouquet',
            'slug' => 'spring-pastel-bouquet',
            'description' => 'A fresh arrangement of seasonal flowers.',
            'price' => 2500.00,
        ]);
    }
}
