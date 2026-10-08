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
        $query = Payment::with(['student.user', 'student.department', 'student.programme', 'receipt']);

        // Filter by student email if provided
        if ($request->filled('email')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->whereHas('user', function($uq) use ($request) {
                    $uq->where('email', 'like', '%' . $request->email . '%');
                });
            });
        }

        // Filter by school if provided
        if ($request->filled('school_id')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('school_id', $request->school_id);
            });
        }

        // Filter by department if provided
        if ($request->filled('department_id')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        $payments = $query->latest()->paginate(20);

        $schools = \App\Models\School::orderBy('name')->get();
        $departments = \App\Models\Department::orderBy('name')->get();

        return view('admin.payments.history', compact('payments', 'schools', 'departments'));
    }

    public function export(Request $request)
    {
        $fileName = 'payment_history_' . now()->format('YmdHis') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, ran-expire",
            "Expires"             => "0"
        ];

        $callback = function() use ($request) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Receipt No', 'Student Name', 'Department', 'Programme', 'Amount', 'Status', 'Date']);

            $query = Payment::with(['student.user', 'student.department', 'student.programme', 'receipt']);

            if ($request->filled('email')) {
                $query->whereHas('student', function($q) use ($request) {
                    $q->whereHas('user', function($uq) use ($request) {
                        $uq->where('email', 'like', '%' . $request->email . '%');
                    });
                });
            }

            if ($request->filled('school_id')) {
                $query->whereHas('student', function($q) use ($request) {
                    $q->where('school_id', $request->school_id);
                });
            }

            if ($request->filled('department_id')) {
                $query->whereHas('student', function($q) use ($request) {
                    $q->where('department_id', $request->department_id);
                });
            }

            $query->latest()->chunk(100, function ($payments) use ($file) {
                foreach ($payments as $payment) {
                    fputcsv($file, [
                        $payment->receipt->receipt_no ?? 'N/A',
                        $payment->student->user->name ?? 'Unknown',
                        $payment->student->department->name ?? 'N/A',
                        $payment->student->programme->name ?? 'N/A',
                        number_format($payment->amount, 2),
                        $payment->status,
                        $payment->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Mark a payment as successful (Manual Confirmation).
     */
    public function confirmPayment($id)
    {
        try {
            $payment = Payment::findOrFail($id);

            if ($payment->status === 'success') {
                return redirect()->back()->with('info', 'Payment is already marked as successful.');
            }

            \Illuminate\Support\Facades\DB::transaction(function () use ($payment) {
                // Update payment status
                $payment->update(['status' => 'success']);

                // Create receipt if it doesn't exist
                \App\Models\Receipt::firstOrCreate(
                    ['payment_id' => $payment->id],
                    [
                        'receipt_no' => 'MANUAL-' . strtoupper(uniqid()),
                        'issued_at' => now(),
                    ]
                );
            });

            return redirect()->back()->with('success', 'Payment confirmed successfully. Receipt has been issued.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to confirm payment: ' . $e->getMessage());
        }
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
