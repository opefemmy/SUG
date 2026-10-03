<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReceiptVerificationController extends Controller
{
    /**
     * Publicly verify a payment receipt.
     */
    public function verify($payment_id): View
    {
        $payment = Payment::with(['student.user', 'student.biodata', 'student.programme', 'student.level'])->findOrFail($payment_id);

        return view('public.receipt_verify', compact('payment'));
    }
}
