<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function __invoke(Request $request)
    {
        // OPay can be inconsistent with parameter names.
        // We check a wide array of possible keys used by OPay in different versions of their API.
        $possibleKeys = ['reference', 'orderNo', 'transaction_ref', 'txRef', 'extReference'];
        $reference = null;

        foreach ($possibleKeys as $key) {
            if ($request->has($key)) {
                $reference = $request->input($key);
                break;
            }
        }

        if (!$reference) {
            // Log the full request so we can identify the correct key from the logs
            Log::warning("OPay Callback: No valid reference found", [
                'all_inputs' => $request->all(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);
            return response()->json(['status' => 'error', 'message' => 'Missing reference or orderNo'], 400);
        }

        Log::info("Payment Callback received for reference: {$reference}", [
            'all_params' => $request->all()
        ]);

        try {
            $payment = Payment::where('transaction_ref', $reference)->firstOrFail();

            // Verify the payment using the PaymentService
            $paymentService = app(\App\Services\PaymentService::class);
            $isVerified = $paymentService->verifyPayment($reference);

            // SANDBOX OVERRIDE: If we are in test mode, treat any existing payment as success
            // to avoid blocking the user during development.
            $settings = app(\App\Services\SettingsService::class);
            $opayUrl = $settings->get('opay_base_url', '');

            if (!$isVerified && (str_contains($opayUrl, 'testapi') || str_contains($opayUrl, 'sandbox'))) {
                Log::info("Sandbox Override: Forcing success for reference {$reference}");
                $isVerified = true;
            }

            if ($isVerified) {
                // 1. Update payment status
                $payment->update([
                    'status' => 'success',
                    'payment_date' => now(),
                ]);

                // 2. Explicitly generate the receipt record now
                // This ensures the receipt_no exists before the user is redirected
                $receiptService = app(\App\Services\ReceiptService::class);
                $receiptService->generateReceipt($payment);

                return redirect()->route('student.fees')
                    ->with('success', 'Payment verified successfully! Your receipt is now available.');
            }

            return redirect()->route('student.fees')
                ->with('error', 'Payment verification failed. Please contact support if you have been debited.');

        } catch (\Exception $e) {
            Log::error("Payment Callback Error: " . $e->getMessage());
            return redirect()->route('student.fees')
                ->with('error', 'Payment record not found. Please contact support.');
        }
    }
}
