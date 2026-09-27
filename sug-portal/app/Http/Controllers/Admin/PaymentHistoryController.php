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
}
