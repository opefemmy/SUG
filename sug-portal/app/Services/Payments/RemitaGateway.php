<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RemitaGateway extends PaymentGatewayInterface
{
    protected $apiKey;
    protected $merchantId;

    public function __construct()
    {
        $this->apiKey = config('services.remita.api_key');
        $this->merchantId = config('services.remita.merchant_id');
    }

    public function initializePayment(array $data): array
    {
        // Remita typically uses a different flow (RRA), implemented here as a generic API call
        $response = Http::withHeaders(['apikey' => $this->apiKey])
            ->post('https://remita.net/remita/exapp/api/v1/payments', [
                'merchantId' => $this->merchantId,
                'amount' => $data['amount'],
                'callbackUrl' => $data['callback_url'],
                'customerId' => $data['email'],
            ]);

        if ($response->successful()) {
            return $response->json()['data'];
        }

        Log::error('Remita initialization failed: ' . $response->body());
        return [];
    }

    public function verifyTransaction(string $reference): bool
    {
        $response = Http::withHeaders(['apikey' => $this->apiKey])
            ->get("https://remita.net/remita/exapp/api/v1/payments/{$reference}");

        if ($response->successful() && $response->json()['status'] === 'success') {
            return true;
        }

        return false;
    }

    public function handleWebhook(array $payload, string $signature): bool
    {
        // Remita webhook verification logic
        return true;
    }
}
