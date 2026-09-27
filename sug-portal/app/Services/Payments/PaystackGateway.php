<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackGateway extends PaymentGatewayInterface
{
    protected $secretKey;

    public function __construct()
    {
        $this->secretKey = config('services.paystack.secret_key');
    }

    public function initializePayment(array $data): array
    {
        $response = Http::withToken($this->secretKey)
            ->post('https://api.paystack.co/transaction/initialize', [
                'email' => $data['email'],
                'amount' => $data['amount'] * 100, // Paystack uses kobo/cents
                'callback_url' => $data['callback_url'],
                'metadata' => $data['metadata'],
            ]);

        if ($response->successful()) {
            return $response->json()['data'];
        }

        Log::error('Paystack initialization failed: ' . $response->body());
        return [];
    }

    public function verifyTransaction(string $reference): bool
    {
        $response = Http::withToken($this->secretKey)
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        if ($response->successful() && $response->json()['data']['status'] === 'success') {
            return true;
        }

        return false;
    }

    public function handleWebhook(array $payload, string $signature): bool
    {
        // Paystack webhook verification logic
        return true;
    }
}
