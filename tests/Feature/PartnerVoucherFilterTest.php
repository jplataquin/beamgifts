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

        Voucher::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'status' => 'active',
            'unique_token' => 'token1',
            'qr_payload' => 'payload1',
        ]);

        Voucher::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'status' => 'claimed',
            'unique_token' => 'token2',
            'qr_payload' => 'payload2',
        ]);

        $response = $this->actingAs($this->partner, 'partner')
            ->get(route('partner.vouchers.index', ['status' => 'active']));

        $response->assertStatus(200);
        $response->assertSee('token1');
        $response->assertDontSee('token2');
    }

    public function test_partner_can_filter_vouchers_by_date_range()
    {
        $order = Order::create(['gifter_id' => $this->gifter->id, 'total_amount' => 300, 'status' => 'paid']);

        $v1 = Voucher::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'status' => 'active',
            'unique_token' => 'old_token',
            'qr_payload' => 'payload_old',
        ]);
        $v1->forceFill(['created_at' => now()->subDays(10)])->save();

        $v2 = Voucher::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
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
        $response->assertSee('new_token');
        $response->assertDontSee('old_token');
    }
}
