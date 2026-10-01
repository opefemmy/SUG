<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveGateway extends PaymentGatewayInterface
{
    protected $secretKey;

    public function __construct()
    {
        $this->secretKey = config('services.flutterwave.secret_key');
    }

    public function initializePayment(array $data): array
    {
        $response = Http::withToken($this->secretKey)
            ->post('https://api.flutterwave.com/v3/payments', [
                'tx_ref' => $data['reference'],
                'amount' => $data['amount'],
                'currency' => 'NGN',
                'redirect_url' => $data['callback_url'],
                'customer' => [
                    'email' => $data['email'],
                    'name' => $data['name'],
                ],
                'customizations' => [
                    'title' => 'SUG Payment',
                    'description' => 'Payment for ' . ($data['fee_name'] ?? 'SUG Fees'),
                ],
            ]);

        if ($response->successful()) {
            return $response->json()['data'];
        }

        Log::error('Flutterwave initialization failed: ' . $response->body());
        return [];
    }

    public function verifyTransaction(string $reference): string
    {
        $response = Http::withToken($this->secretKey)
            ->get("https://api.flutterwave.com/v3/transactions/{$reference}/verify");

        if ($response->successful()) {
            $status = $response->json()['data']['status'] ?? 'pending';

            if ($status === 'successful') {
                return 'success';
            }

            if ($status === 'failed') {
                return 'failed';
            }
        }

        return 'pending';
    }

    public function handleWebhook(array $payload, string $signature): bool
    {
        // Flutterwave webhook verification logic
        return true;
    }
}
