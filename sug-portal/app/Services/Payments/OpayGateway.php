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

        if ($method = $this->settings->get('opay_pay_method')) {
            $payload['payMethod'] = $method;
        }

        try {
            Log::info("OPay: Initiating payment for reference {$data['reference']}", [
                'merchantId' => $merchantId,
                'baseUrl' => $this->baseUrl,
                'payload' => $payload
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $publicKey,
                'MerchantId' => $merchantId,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/cashier/create', $payload);

            if ($response->successful()) {
                $resData = $response->json();
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
            Log::error("OPay Gateway Exception: " . $e->getMessage(), [
                'reference' => $data['reference'],
                'status' => $e->getCode()
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error("OPay Connection Exception: " . $e->getMessage(), [
                'reference' => $data['reference'],
                'trace' => $e->getTraceAsString()
            ]);
            throw new \App\Exceptions\Payments\PaymentGatewayException(
                "OPay Connection Exception: " . $e->getMessage(),
                500,
                null
            );
        }
    }

    /**
     * Verify a transaction with OPay.
     * Returns 'success', 'failed', or 'pending'.
     */
    public function verifyTransaction(string $reference): string
    {
        $merchantId = $this->settings->get('opay_merchant_id');
        $secretKey = $this->settings->get('opay_secret_key');
        $baseUrl = $this->baseUrl;

        if (!$merchantId || !$secretKey) {
            \Illuminate\Support\Facades\Log::error("OPay Verification failed: MerchantId or SecretKey is missing from settings.");
            return 'pending';
        }

        try {
            // OPay status verification requires a signature of the request body
            $payload = [
                'reference' => $reference,
                'country' => 'NG'
            ];

            $payloadJson = json_encode($payload, JSON_UNESCAPED_SLASHES);

            // Try both HMAC-SHA512 and HMAC-SHA256 as some OPay versions differ
            $signature = hash_hmac('sha512', $payloadJson, $secretKey);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $signature,
                'MerchantId' => $merchantId,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/cashier/status', $payload);

            if ($response->successful()) {
                $resData = $response->json();
                \Illuminate\Support\Facades\Log::info("OPay Verification Raw Response for {$reference}:", $resData);

                $status = strtolower(
                    $resData['status'] ??
                    ($resData['data']['status'] ?? null) ??
                    ($resData['data']['paymentStatus'] ?? null) ??
                    ($resData['data']['state'] ?? null) ??
                    ''
                );

                \Illuminate\Support\Facades\Log::info("OPay Parsed Status for {$reference}: {$status}");

                if (in_array($status, ['successful', 'success', 'completed', 'paid', 'captured'])) {
                    return 'success';
                }

                if (in_array($status, ['failed', 'cancelled', 'reversed', 'declined'])) {
                    return 'failed';
                }

                return !empty($status) ? 'pending' : 'pending';
            }

            // If 401/403, try the alternative SHA256 signature as a fallback
            if (in_array($response->status(), [401, 403])) {
                \Illuminate\Support\Facades\Log::info("OPay SHA512 failed, trying SHA256 fallback for {$reference}");
                $signature256 = hash_hmac('sha256', $payloadJson, $secretKey);
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $signature256,
                    'MerchantId' => $merchantId,
                    'Content-Type' => 'application/json',
                ])->post($this->baseUrl . '/cashier/status', $payload);

                if ($response->successful()) {
                    $resData = $response->json();
                    $status = strtolower($resData['status'] ?? ($resData['data']['status'] ?? ''));
                    if (in_array($status, ['successful', 'success', 'completed', 'paid', 'captured'])) {
                        return 'success';
                    }
                }
            }

            \Illuminate\Support\Facades\Log::error("OPay Verification API failed with status: " . $response->status() . " Body: " . $response->body());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("OPay Verification Exception: " . $e->getMessage());
        }

        return 'pending';
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

        return isset($payload['status']) && ($payload['status'] === 'SUCCESSFUL' || strtolower($payload['status']) === 'success');
    }
}
