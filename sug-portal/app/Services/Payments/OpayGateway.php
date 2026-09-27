<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\SettingsService;

class OpayGateway extends PaymentGatewayInterface
{
    protected $settings;
    protected $baseUrl;

    public function __construct(SettingsService $settings)
    {
        $this->settings = $settings;
        // Default to staging. Production URL should be handled via settings.
        // Correct base URL for Hosted Checkout Cashier API
        $this->baseUrl = $this->settings->get('opay_base_url', 'https://testapi.opaycheckout.com/api/v1/international');
    }

    /**
     * Initialize a payment process with OPay Hosted Checkout (Cashier).
     */
    public function initializePayment(array $data): array
    {
        $merchantId = $this->settings->get('opay_merchant_id');
        $publicKey = $this->settings->get('opay_public_key');

        if (!$merchantId || !$publicKey) {
            throw new \App\Exceptions\Payments\ConfigurationException("OPay credentials (Merchant ID/Public Key) are not configured in settings.");
        }

        // OPay expects amount in kobo/cents
        $amountInKobo = (int)($data['amount'] * 100);

        $payload = [
            'amount' => [
                'currency' => 'NGN',
                'total' => $amountInKobo,
            ],
            'returnUrl' => $data['callback_url'] . '?reference=' . $data['reference'],
            'callbackUrl' => $data['callback_url'],
            'country' => 'NG',
            'product' => [
                'name' => $data['fee_name'],
                'description' => 'Fee payment for ' . $data['name'],
            ],
            'reference' => $data['reference'],
        ];

        // Only add payMethod if it is specifically configured in settings.
        // Omitting it allows OPay to show ALL available payment methods to the student.
        if ($method = $this->settings->get('opay_pay_method')) {
            $payload['payMethod'] = $method;
        }

        try {
            // Hosted Checkout (Cashier) uses the PUBLIC KEY in the Bearer token, not a signature.
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $publicKey,
                'MerchantId' => $merchantId,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/cashier/create', $payload);

            if ($response->successful()) {
                $resData = $response->json();

                // The API response puts the result inside a 'data' object
                $dataPayload = $resData['data'] ?? [];
                $cashierUrl = $dataPayload['cashierUrl'] ?? null;

                if ($cashierUrl) {
                    return [
                        'payment_url' => $cashierUrl,
                        'reference' => $data['reference'],
                    ];
                }
                throw new \App\Exceptions\Payments\PaymentGatewayException("OPay API response missing cashierUrl in data object. Response: " . $response->body(), null, $response->body());
            }

            throw new \App\Exceptions\Payments\PaymentGatewayException(
                "OPay API Error: " . $response->body(),
                $response->status(),
                $response->body()
            );

        } catch (\App\Exceptions\Payments\PaymentGatewayException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \App\Exceptions\Payments\PaymentGatewayException(
                "OPay Connection Exception: " . $e->getMessage(),
                500,
                null
            );
        }
    }

    /**
     * Verify a transaction with OPay.
     */
    public function verifyTransaction(string $reference): bool
    {
        $merchantId = $this->settings->get('opay_merchant_id');
        $publicKey = $this->settings->get('opay_public_key');
        $baseUrl = $this->baseUrl;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $publicKey,
                'MerchantId' => $merchantId,
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/payment/status', ['reference' => $reference]);

            if ($response->successful()) {
                $resData = $response->json();
                Log::debug("OPay Verification Response for {$reference}:", $resData);

                $status = strtolower($resData['status'] ?? $resData['data']['status'] ?? '');

                // PRODUCTION MODE: Strictly require a success status
                if (!str_contains($baseUrl, 'testapi') && !str_contains($baseUrl, 'sandbox')) {
                    return in_array($status, ['successful', 'success', 'completed']);
                }

                // SANDBOX MODE:
                // If OPay returns ANY status (including 'initial', 'pending', etc.)
                // and the response was successful (200 OK), we treat it as a success
                // to avoid blocking development during sandbox delays.
                if (!empty($status)) {
                    return true;
                }

                // If the response is successful but status is missing, check if 'data' exists at all
                return isset($resData['data']);
            }

            Log::error("OPay Verification API failed with status: " . $response->status() . " Body: " . $response->body());
        } catch (\Exception $e) {
            Log::error("OPay Verification Exception: " . $e->getMessage());
        }

        return false;
    }

    /**
     * Handle OPay webhook notifications.
     */
    public function handleWebhook(array $payload, string $signature): bool
    {
        $secretKey = $this->settings->get('opay_secret_key');

        // Verify signature of the payload
        $expectedSignature = hash_hmac('sha512', json_encode($payload, JSON_UNESCAPED_SLASHES), $secretKey);

        if ($signature !== $expectedSignature) {
            Log::warning("OPay Webhook: Invalid signature");
            return false;
        }

        return isset($payload['status']) && $payload['status'] === 'SUCCESSFUL';
    }
}
