<?php

namespace App\Http\Controllers;

use App\Services\ReceiptService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use App\Models\Payment;

class ReceiptController extends Controller
{
    protected $receiptService;

    public function __construct(ReceiptService $receiptService)
    {
        $this->receiptService = $receiptService;
    }

    /**
     * Download the PDF receipt.
     */
    public function download(Payment $payment): \Illuminate\Http\Response
    {
        // Ensure payment was successful
        if ($payment->status !== 'success') {
            abort(403, 'Cannot generate receipt for an unsuccessful payment.');
        }

        $result = $this->receiptService->generateReceipt($payment);
        return $result['pdf']->download("receipt_{$payment->transaction_ref}.pdf");
    }

    /**
     * View the receipt in browser.
     */
    public function view(Payment $payment): \Illuminate\Http\Response
    {
        if ($payment->status !== 'success') {
            abort(403, 'Cannot view receipt for an unsuccessful payment.');
        }

        $result = $this->receiptService->generateReceipt($payment);
        return $result['pdf']->stream();
    }

    /**
     * Publicly verify a receipt via QR code.
     */
    public function verify(string $receiptNo): View
    {
        $payment = $this->receiptService->verifyReceipt($receiptNo);

        return view('receipts.verify', compact('payment'));
    }
}
