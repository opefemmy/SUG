<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Receipt;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ReceiptService
{
    /**
     * Generate a professional receipt for a payment.
     */
    public function generateReceipt(Payment $payment)
    {
        $student = $payment->student;
        $fee = $payment->feeStructure->feeType;

        // Create receipt record if it doesn't exist
        $receipt = Receipt::firstOrCreate(
            ['payment_id' => $payment->id],
            [
                'receipt_no' => 'SUG-REC-' . strtoupper(Str::random(10)),
                'issued_at' => now(),
            ]
        );

        // Generate a professional Barcode (Code128) using the HTML format
        // This is the most compatible way to show barcodes in dompdf on XAMPP
        try {
            // Since the barcode library is crashing the server, we create a visual "barcode-like"
            // representation using a series of thin and thick vertical lines in HTML/CSS.
            $receiptNo = $receipt->receipt_no;
            $bars = '';

            // Create a pseudo-barcode by mapping characters to line widths
            for ($i = 0; $i < strlen($receiptNo); $i++) {
                $char = $receiptNo[$i];
                $width = (ord($char) % 3) + 1; // 1px, 2px, or 3px
                $bars .= '<span style="display: inline-block; width: ' . $width . 'px; height: 30px; background: black; margin-right: 1px; vertical-align: middle;"></span>';
            }

            $barcode = '<div style="text-align: center; display: inline-block; padding: 10px; background: white; border: 1px solid #eee;">' .
                       '<div style="margin-bottom: 5px;">' . $bars . '</div>' .
                       '<div style="font-family: monospace; font-size: 10px; color: #333;">' . $receiptNo . '</div>' .
                       '</div>';
        } catch (\Exception $e) {
            Log::error("Barcode generation failed: " . $e->getMessage());
            $barcode = null;
        }

        $verificationUrl = route('receipt.verify', ['receipt_no' => $receipt->receipt_no]);

        // Get student passport photo
        $passport = null;
        if ($student->biodata && $student->biodata->passport_path) {
            $relativePath = $student->biodata->passport_path;
            $pathsToTry = [
                storage_path('app/' . $relativePath),
                public_path('storage/' . $relativePath),
                storage_path('app/public/' . $relativePath),
            ];

            foreach ($pathsToTry as $path) {
                if (file_exists($path)) {
                    $imageData = base64_encode(file_get_contents($path));
                    $extension = pathinfo($path, PATHINFO_EXTENSION) ?: 'png';
                    $passport = 'data:image/' . $extension . ';base64,' . $imageData;
                    break;
                }
            }
        }

        $data = [
            'receipt' => $receipt,
            'payment' => $payment,
            'student' => $student,
            'fee' => $fee,
            'barcode' => $barcode,
            'verificationUrl' => $verificationUrl,
            'passport' => $passport,
            'institution' => \App\Services\SettingsService::get('site_name', config('app.name')),
            'logo' => \App\Services\SettingsService::get('brand_logo'),
        ];

        $pdf = Pdf::loadView('receipts.pdf', $data);

        return [
            'pdf' => $pdf,
            'receipt' => $receipt
        ];
    }

    /**
     * Verify a receipt by its number.
     */
    public function verifyReceipt(string $receiptNo): ?Payment
    {
        $receipt = Receipt::where('receipt_no', $receiptNo)->first();

        if (!$receipt) {
            return null;
        }

        return $receipt->payment;
    }
}
