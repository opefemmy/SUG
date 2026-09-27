<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Student;
use App\Models\FeeStructure;
use App\Services\Payments\PaymentGatewayInterface;
use App\Services\Payments\PaystackGateway;
use App\Services\Payments\FlutterwaveGateway;
use App\Services\Payments\RemitaGateway;
use App\Services\Payments\OpayGateway;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymentService
{
    protected $gateways = [];

    public function __construct(
        PaystackGateway $paystack,
        FlutterwaveGateway $flutterwave,
        RemitaGateway $remita,
        OpayGateway $opay
    ) {
        $this->gateways = [
            'paystack' => $paystack,
            'flutterwave' => $flutterwave,
            'remita' => $remita,
            'opay' => $opay,
        ];
    }

    /**
     * Initialize a payment.
     */
    public function initiatePayment(Student $student, FeeStructure $fee, string $gatewayName, array $options = []): array
    {
        if (!isset($this->gateways[$gatewayName])) {
            throw new Exception("Unsupported payment gateway: {$gatewayName}");
        }

        $gateway = $this->gateways[$gatewayName];
        $reference = 'SUG-' . strtoupper(uniqid());

        $payment = Payment::create([
            'student_id' => $student->id,
            'fee_structure_id' => $fee->id,
            'amount' => $fee->amount,
            'transaction_ref' => $reference,
            'payment_gateway' => $gatewayName,
            'status' => 'initiated',
        ]);

        $paymentData = [
            'email' => $student->user->email,
            'name' => $student->user->name,
            'amount' => $fee->amount,
            'reference' => $reference,
            'callback_url' => route('payment.callback'),
            'fee_name' => $fee->feeType->name,
            'metadata' => [
                'payment_id' => $payment->id,
                'student_id' => $student->id,
            ],
        ];

        $result = $gateway->initializePayment($paymentData);

        return [
            'payment' => $payment,
            'gateway_data' => $result,
        ];
    }

    /**
     * Verify and complete a payment.
     */
    public function verifyPayment(string $reference): bool
    {
        $payment = Payment::where('transaction_ref', $reference)->firstOrFail();
        $gateway = $this->gateways[$payment->payment_gateway] ?? null;

        if (!$gateway) {
            throw new Exception("Gateway configuration not found for this payment.");
        }

        if ($gateway->verifyTransaction($reference)) {
            DB::transaction(function () use ($payment) {
                $payment->update([
                    'status' => 'success',
                    'payment_date' => now(),
                ]);

                // Trigger receipt generation here (to be implemented in Phase 7)
            });
            return true;
        }

        $payment->update(['status' => 'failed']);
        return false;
    }
}
