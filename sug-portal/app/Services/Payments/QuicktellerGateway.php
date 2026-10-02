<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\SettingsService;

class QuicktellerGateway extends PaymentGatewayInterface
{
    protected $settings;
    protected $baseUrl;

    public function __construct(SettingsService $settings)
    {
        $this->settings = $settings;
        // Official Quickteller API-First (Pay Bill) Endpoint
        $this->baseUrl = $this->settings->get('quickteller_base_url', 'https://sandbox.interswitchng.com/paymentgateway/api/v1');
    }

    /**
     * Initialize a payment process using the API-First (Pay Bill) integration.
     */
    public function initializePayment(array $data): array
    {
        $merchantCode = $this->settings->get('quickteller_merchant_code');
        $payableCode = $this->settings->get('quickteller_payable_code');

        if (!$merchantCode || !$payableCode) {
            throw new \App\Exceptions\Payments\ConfigurationException("Quickteller credentials (Merchant Code/Payable Code) are not configured in settings.");
        }

        // Interswitch requires amount in minor currency (kobo)
        $amountInKobo = (int)($data['amount'] * 100);

        $payload = [
            'merchantCode' => $merchantCode,
            'payableCode' => $payableCode,
            'amount' => $amountInKobo,
            'redirectUrl' => $data['callback_url'] . '?reference=' . $data['reference'],
            'customerId' => $data['reference'],
            'currencyCode' => '566', // NGN
            'customerEmail' => $data['email'] ?? 'student@example.com',
        ];

        try {
            Log::info("Quickteller: Initiating API-First payment for reference {$data['reference']}", [
                'payload' => $payload
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/paybill', $payload);

            if ($response->successful()) {
                $resData = $response->json();
                $paymentUrl = $resData['paymentUrl'] ?? null;

                if ($paymentUrl) {
                    return [
                        'payment_url' => $paymentUrl,
                        'reference' => $data['reference'],
                    ];
                }
                throw new \App\Exceptions\Payments\PaymentGatewayException("Quickteller API response missing paymentUrl. Response: " . $response->body(), null, $response->body());
            }

            throw new \App\Exceptions\Payments\PaymentGatewayException(
                "Quickteller API Error: " . $response->body(),
                $response->status(),
                $response->body()
            );

        } catch (\App\Exceptions\Payments\PaymentGatewayException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \App\Exceptions\Payments\PaymentGatewayException(
                "Quickteller Connection Exception: " . $e->getMessage(),
                500,
                null
            );
        }
    }

    /**
     * Verify a transaction using the official GetTransaction endpoint.
     * Returns 'success', 'failed', or 'pending'.
     */
    public function verifyTransaction(string $reference): string
    {
        $merchantCode = $this->settings->get('quickteller_merchant_code');

        try {
            // Official verification endpoint for collections
            $verifyUrl = 'https://sandbox.interswitchng.com/collections/api/v1/gettransaction';

            // Quickteller verification requires merchantcode, transactionreference and amount
            // Note: Since we don't have the amount here, we typically fetch it from the Payment model
            // However, based on the interface, we attempt verification with provided reference
            $response = Http::get($verifyUrl, [
                'merchantcode' => $merchantCode,
                'transactionreference' => $reference,
            ]);

            if ($response->successful()) {
                $resData = $response->json();
                Log::debug("Quickteller Verification Raw Response for {$reference}:", $resData);

                // Per documentation: ResponseCode "00" indicates success
                $responseCode = $resData['ResponseCode'] ?? $resData['data']['ResponseCode'] ?? null;

                if ($responseCode === '00') {
                    return 'success';
                }

                if (in_array($responseCode, ['01', '02', '03'])) {
                    return 'failed';
                }

                return 'pending';
            }

            Log::error("Quickteller Verification API failed with status: " . $response->status() . " Body: " . $response->body());
        } catch (\Exception $e) {
            Log::error("Quickteller Verification Exception: " . $e->getMessage());
        }

        return 'pending';
    }

    /**
     * Handle Quickteller webhook notifications.
     */
    public function handleWebhook(array $payload, string $signature): bool
    {
        // Basic status check as per standard Interswitch webhook payloads
        return isset($payload['status']) && in_array(strtolower($payload['status']), ['successful', 'success']);
    }
}
