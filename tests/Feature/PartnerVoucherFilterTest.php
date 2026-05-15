<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Category;
use App\Models\Partner;
use App\Models\Store;
use App\Models\Product;
use App\Models\Order;
use App\Models\Gifter;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerVoucherFilterTest extends TestCase
{
    use RefreshDatabase;

    protected $partner;
    protected $store;
    protected $product;
    protected $gifter;

    protected function setUp(): void
    {
        parent::setUp();

        $city = City::create(['name' => 'Test City', 'slug' => 'test-city']);
        $category = Category::create(['name' => 'Test Category', 'slug' => 'test-category']);

        $this->partner = Partner::create([
            'name' => 'Test Partner',
            'email' => 'partner@test.com',
            'business_name' => 'Test Business',
            'password' => bcrypt('password'),
        ]);

        $this->store = Store::create([
            'partner_id' => $this->partner->id,
            'name' => 'Test Store',
            'slug' => 'test-store',
        ]);

        $this->product = Product::create([
            'store_id' => $this->store->id,
            'category_id' => $category->id,
            'category' => $category->name,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 100,
        ]);

        $this->gifter = Gifter::create([
            'first_name' => 'Test',
            'last_name' => 'Gifter',
            'email' => 'gifter@test.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_partner_can_filter_vouchers_by_status()
    {
        $order = Order::create(['gifter_id' => $this->gifter->id, 'total_amount' => 200, 'status' => 'paid']);

        $product1 = Product::create([
            'store_id' => $this->store->id,
            'category_id' => $this->product->category_id,
            'category' => $this->product->category,
            'name' => 'Active Product Name',
            'slug' => 'active-product',
            'price' => 100,
        ]);

        $product2 = Product::create([
            'store_id' => $this->store->id,
            'category_id' => $this->product->category_id,
            'category' => $this->product->category,
            'name' => 'Claimed Product Name',
            'slug' => 'claimed-product',
            'price' => 100,
        ]);

        Voucher::create([
            'order_id' => $order->id,
            'product_id' => $product1->id,
            'status' => 'active',
            'unique_token' => 'token1',
            'qr_payload' => 'payload1',
        ]);

        Voucher::create([
            'order_id' => $order->id,
            'product_id' => $product2->id,
            'status' => 'claimed',
            'unique_token' => 'token2',
            'qr_payload' => 'payload2',
        ]);

        $response = $this->actingAs($this->partner, 'partner')
            ->get(route('partner.vouchers.index', ['status' => 'active']));

        $response->assertStatus(200);
        $response->assertSee('Active Product Name');
        $response->assertDontSee('Claimed Product Name');
    }

    public function test_partner_can_filter_vouchers_by_date_range()
    {
        $order = Order::create(['gifter_id' => $this->gifter->id, 'total_amount' => 300, 'status' => 'paid']);

        $productOld = Product::create([
            'store_id' => $this->store->id,
            'category_id' => $this->product->category_id,
            'category' => $this->product->category,
            'name' => 'Old Product Name',
            'slug' => 'old-product',
            'price' => 100,
        ]);

        $productNew = Product::create([
            'store_id' => $this->store->id,
            'category_id' => $this->product->category_id,
            'category' => $this->product->category,
            'name' => 'New Product Name',
            'slug' => 'new-product',
            'price' => 100,
        ]);

        $v1 = Voucher::create([
            'order_id' => $order->id,
            'product_id' => $productOld->id,
            'status' => 'active',
            'unique_token' => 'old_token',
            'qr_payload' => 'payload_old',
        ]);
        $v1->forceFill(['created_at' => now()->subDays(10)])->save();

        $v2 = Voucher::create([
            'order_id' => $order->id,
            'product_id' => $productNew->id,
            'status' => 'active',
            'unique_token' => 'new_token',
            'qr_payload' => 'payload_new',
        ]);
        $v2->forceFill(['created_at' => now()])->save();

        // Filter for last 5 days
        $response = $this->actingAs($this->partner, 'partner')
            ->get(route('partner.vouchers.index', [
                'from_date' => now()->subDays(5)->format('Y-m-d'),
                'to_date' => now()->format('Y-m-d'),
            ]));

        $response->assertStatus(200);
        $response->assertSee('New Product Name');
        $response->assertDontSee('Old Product Name');
    }
}
