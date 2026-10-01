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
     * Returns 'success', 'failed', or 'pending'.
     */
    public function verifyTransaction(string $reference): string
    {
        return 'pending';
    }

    /**
     * Handle a webhook notification from the gateway.
     */
    public function handleWebhook(array $payload, string $signature): bool
    {
        return false;
    }
}
