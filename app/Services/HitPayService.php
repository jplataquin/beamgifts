<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HitPayService
{
    protected string $apiKey;
    protected string $salt;
    protected bool $isSandbox;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.hitpay.api_key');
        $this->salt = config('services.hitpay.salt');
        $this->isSandbox = config('services.hitpay.sandbox', true);
        $this->baseUrl = $this->isSandbox 
            ? 'https://api.sandbox.hit-pay.com/v1' 
            : 'https://api.hit-pay.com/v1';
    }

    public function createPaymentRequest(array $data)
    {
        $response = Http::withHeaders([
            'X-BUSINESS-API-KEY' => $this->apiKey,
            'X-Requested-With' => 'XMLHttpRequest'
        ])->asJson()->post("{$this->baseUrl}/payment-requests", $data);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('HitPay Payment Request Failed', [
            'status' => $response->status(),
            'body' => $response->body(),
            'payload' => $data
        ]);

        return null;
    }

    public function getPaymentStatus(string $paymentRequestId)
    {
        $response = Http::withHeaders([
            'X-BUSINESS-API-KEY' => $this->apiKey,
        ])->get("{$this->baseUrl}/payment-requests/{$paymentRequestId}");

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function generateSignature(array $data): string
    {
        ksort($data);
        $str = '';
        foreach ($data as $key => $value) {
            if ($key !== 'hmac') {
                $str .= $key . $value;
            }
        }
        return hash_hmac('sha256', $str, $this->salt);
    }
}
