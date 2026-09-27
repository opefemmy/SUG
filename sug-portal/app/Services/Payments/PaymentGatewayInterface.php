<?php

namespace App\Services\Payments;

class PaymentGatewayInterface
{
    /**
     * Initialize a payment process with the gateway.
     * Returns a redirect URL or a payment reference.
     */
    public function initializePayment(array $data): array
    {
        return [];
    }

    /**
     * Verify a transaction with the gateway.
     */
    public function verifyTransaction(string $reference): bool
    {
        return false;
    }

    /**
     * Handle a webhook notification from the gateway.
     */
    public function handleWebhook(array $payload, string $signature): bool
    {
        return false;
    }
}
