<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Receipt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\SettingsService;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReceiptController extends Controller
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * Display a listing of the student's payment receipts.
     */
    public function index()
    {
        $student = \App\Models\Student::where('user_id', Auth::id())->firstOrFail();

        // Fetch payments for the authenticated student using student_id
        $payments = Payment::where('student_id', $student->id)
            ->where('status', 'success')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.receipts.index', [
            'payments' => $payments
        ]);
    }

    /**
     * Download the receipt as a professional PDF.
     */
    public function download($id)
    {
        $payment = Payment::findOrFail($id);
        $student = \App\Models\Student::with('biodata', 'user', 'department', 'programme')->where('user_id', Auth::id())->firstOrFail();

        if ($payment->student_id !== $student->id) {
            abort(403, 'Unauthorized access to this receipt.');
        }

        // --- Handle Receipt Number ---
        // Check if a formal receipt record exists, otherwise create one
        $receipt = Receipt::firstOrCreate(
            ['payment_id' => $payment->id],
            ['receipt_no' => 'REC-' . strtoupper(uniqid())]
        );

        // Resolve logo path for DomPDF
        $logoPath = $this->settingsService->get('site_logo');
        $fullLogoPath = $logoPath
            ? storage_path('app/public/' . $logoPath)
            : storage_path('app/public/branding/1790506165_new logo.png');

        // Resolve passport path for DomPDF
        $passportPath = $student->biodata->passport_path ?? null;
        $fullPassportPath = $passportPath
            ? storage_path('app/public/' . $passportPath)
            : storage_path('app/public/passports/0kI2hdEgLSRszpMzm4nR6nhw7TLK7oF1IFknPnbB.jpg');

        // Generate Verification QR Code
        $verificationUrl = route('receipt.verify', ['payment_id' => $payment->id]);
        $qrCodeSvg = QrCode::size(100)->generate($verificationUrl);
        $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);

        $settings = [
            'site_name' => $this->settingsService->get('site_name', 'SUG Portal'),
        ];

        // Load the professional PDF view
        $pdf = Pdf::loadView('student.receipts.pdf', compact(
            'payment',
            'student',
            'settings',
            'fullLogoPath',
            'fullPassportPath',
            'qrCodeBase64',
            'receipt'
        ));

        return $pdf->download('receipt_'.$payment->id.'.pdf');
    }
}
