<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentComplaint;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentComplaintController extends Controller
{
    /**
     * Display a listing of pending payment complaints.
     */
    public function index()
    {
        $complaints = PaymentComplaint::where('status', 'pending')
            ->with(['student', 'feeStructure'])
            ->latest()
            ->paginate(15);

        return view('admin.complaints.index', compact('complaints'));
    }

    /**
     * Show details of a specific complaint.
     */
    public function show($id)
    {
        $complaint = PaymentComplaint::with(['student', 'feeStructure'])->findOrFail($id);
        return view('admin.complaints.show', compact('complaint'));
    }

    /**
     * Verify a payment complaint and mark as paid.
     */
    public function verify(Request $request, $id)
    {
        $complaint = PaymentComplaint::findOrFail($id);

        try {
            DB::transaction(function () use ($complaint) {
                // 1. Update complaint status
                $complaint->update(['status' => 'verified']);

                // 2. Handle Payment record
                $payment = Payment::where('transaction_ref', $complaint->transaction_ref)->first();

                if ($payment) {
                    // Update existing record to success
                    $payment->update([
                        'status' => 'success',
                        'payment_gateway' => 'manual',
                    ]);
                } else {
                    // Create new successful payment record
                    $payment = Payment::create([
                        'student_id' => $complaint->student_id,
                        'fee_structure_id' => $complaint->fee_structure_id,
                        'amount' => $complaint->amount,
                        'transaction_ref' => $complaint->transaction_ref,
                        'status' => 'success',
                        'payment_gateway' => 'manual',
                    ]);
                }

                // 3. Generate Receipt if not already present
                $receipt = Receipt::where('payment_id', $payment->id)->first();
                if (!$receipt) {
                    $receipt = Receipt::create([
                        'payment_id' => $payment->id,
                        'receipt_no' => 'MANUAL-' . strtoupper(uniqid()),
                        'issued_at' => now(),
                    ]);
                }
            });

            return redirect()->route('admin.payment.complaints.index')
                ->with('success', 'Payment verified successfully. Receipt generated.');
        } catch (\Exception $e) {
            Log::error("Payment Complaint Verification Error: " . $e->getMessage());
            return redirect()->route('admin.payment.complaints.index')
                ->with('error', 'An error occurred during verification: ' . $e->getMessage());
        }
    }

    /**
     * Reject a payment complaint.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $complaint = PaymentComplaint::findOrFail($id);
        $complaint->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->route('admin.payment.complaints.index')
            ->with('success', 'Complaint rejected. Student has been notified via status update.');
    }
}
