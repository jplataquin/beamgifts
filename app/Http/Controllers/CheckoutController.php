<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\Setting;
use App\Services\HitPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected HitPayService $hitpay;

    public function __construct(HitPayService $hitpay)
    {
        $this->hitpay = $hitpay;
    }

    public function store(Request $request, $city_slug)
    {
        $city = app('current_city');
        $cartKey = "cart_{$city->id}";
        $cart = Session::get($cartKey, []);

        if (empty($cart)) {
            return redirect()->route('city.home', $city_slug)->with('error', 'Your cart is empty.');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            if ($item['quantity'] > 3) {
                return redirect()->route('cart.index', $city_slug)->with('error', 'Maximum quantity per item is 3.');
            }
            $subtotal += $item['price'] * $item['quantity'];
        }

        $markupPercent = Setting::get('global_markup_percentage', 0);
        $markupAmount = ($subtotal * $markupPercent) / 100;
        $total = $subtotal + $markupAmount;

        return DB::transaction(function () use ($cart, $total, $city, $city_slug) {
            // 1. Create Order
            $order = Order::create([
                'gifter_id' => Auth::id(),
                'total_amount' => $total,
                'status' => 'pending',
            ]);

            // 2. Create Order Items
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);
            }

            // 3. Prepare HitPay Request
            $paymentData = [
                'amount' => $total,
                'currency' => 'PHP',
                'reference_number' => 'BEAM-' . $order->id,
                'redirect_url' => route('checkout.callback', ['city_slug' => $city->slug]),
                'email' => Auth::user()->email,
                'name' => Auth::user()->name,
            ];

            // Only add webhook if it's not a local address
            $webhookUrl = route('checkout.webhook');
            if (!str_contains($webhookUrl, '127.0.0.1') && !str_contains($webhookUrl, 'localhost')) {
                $paymentData['webhook'] = $webhookUrl;
            }

            $paymentRequest = $this->hitpay->createPaymentRequest($paymentData);

            if ($paymentRequest && isset($paymentRequest['url'])) {
                $order->update(['hitpay_transaction_id' => $paymentRequest['id']]);
                return redirect($paymentRequest['url']);
            }

            Log::error('HitPay initialization failed for order: ' . $order->id, [
                'request_data' => $paymentData,
                'response' => $paymentRequest
            ]);

            throw new \Exception('HitPay initialization failed.');
        });
    }

    public function callback(Request $request, $city_slug)
    {
        $status = $request->input('status');
        $reference = $request->input('reference');
        
        Log::info('HitPay Callback Params:', $request->all());

        if ($status === 'completed' || $status === 'success' || app()->isLocal()) {
            // Localhost Webhook Bypass
            if (app()->isLocal()) {
                Log::info('Local environment detected, processing bypass...');
                $order = null;
                
                if ($reference && str_starts_with($reference, 'BEAM-')) {
                    $orderId = str_replace('BEAM-', '', $reference);
                    $order = Order::find($orderId);
                }
                
                if (!$order) {
                    // Try to find the latest pending order for the current user as a last resort for local dev
                    $order = Order::where('gifter_id', Auth::id())
                        ->where('status', 'pending')
                        ->latest()
                        ->first();
                    Log::info('Local bypass: Found order by fallback (latest pending): ' . ($order ? $order->id : 'none'));
                }

                if ($order) {
                    if ($order->status !== 'paid') {
                        $this->processSuccessfulOrder($order);
                        Log::info('Local bypass: Order ' . $order->id . ' processed successfully.');
                    }
                } else {
                    Log::warning('Local bypass: No order found to process.');
                }
            }

            Session::forget("cart_" . app('current_city')->id);
            return view('checkout.success');
        }

        return redirect()->route('cart.index', $city_slug)->with('error', 'Payment was not completed. Status: ' . $status);
    }

    public function webhook(Request $request)
    {
        $data = $request->all();
        $paymentRequestId = $data['payment_request_id'] ?? null;

        if (!$paymentRequestId) return response('No ID', 400);

        $order = Order::where('hitpay_transaction_id', $paymentRequestId)->first();

        if ($order && $data['status'] === 'completed' && $order->status !== 'paid') {
            $this->processSuccessfulOrder($order);
        }

        return response('OK', 200);
    }

    /**
     * Process order fulfillment and voucher generation.
     */
    private function processSuccessfulOrder(Order $order)
    {
        DB::transaction(function () use ($order) {
            $order->update(['status' => 'paid']);

            // Generate Vouchers for each quantity of each item
            foreach ($order->items()->with('product')->get() as $item) {
                for ($i = 0; $i < $item->quantity; $i++) {
                    $token = Str::random(32);
                    Voucher::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'price' => $item->product->price ?? $item->price,
                        'markup_price' => $item->product->markup_price ?? null,
                        'unique_token' => $token,
                        'qr_payload' => $token,
                        'status' => 'active',
                        'expires_at' => now()->addMonths(6),
                    ]);
                }
            }
        });
    }
}
