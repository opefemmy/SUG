<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;

class PaymentHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['student.user', 'receipt']);

        // Filter by student email if provided
        if ($request->has('email')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->whereHas('user', function($uq) use ($request) {
                    $uq->where('email', 'like', '%' . $request->email . '%');
                });
            });
        }

        $payments = $query->latest()->paginate(20);

        return view('admin.payments.history', compact('payments'));
    }

    /**
     * Mark a manual payment as unpaid (delete the payment and associated receipt).
     */
    public function markAsUnpaid($id)
    {
        try {
            $payment = Payment::findOrFail($id);

            // We only allow removing payments that weren't initiated via the portal (no transaction_ref or specific manual flag)
            // Or simply allow admin to remove any payment as requested.

            \Illuminate\Support\Facades\DB::transaction(function () use ($payment) {
                // Delete associated receipt first
                \App\Models\Receipt::where('payment_id', $payment->id)->delete();
                // Delete the payment
                $payment->delete();
            });

            return redirect()->back()->with('success', 'Payment removed successfully. Student is now marked as unpaid.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to remove payment: ' . $e->getMessage());
        }
    }
}
