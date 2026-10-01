<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\PaymentComplaint;
use App\Models\FeeStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentComplaintController extends Controller
{
    /**
     * Display a listing of the student's payment complaints.
     */
    public function index()
    {
        $studentId = Auth::id();
        // We need to get the student ID from the User model to find the Student model ID
        $student = \App\Models\Student::where('user_id', $studentId)->firstOrFail();

        $complaints = PaymentComplaint::where('student_id', $student->id)
            ->with('feeStructure')
            ->latest()
            ->paginate(10);

        return view('student.complaints.index', compact('complaints'));
    }

    /**
     * Show the form for creating a new payment complaint.
     */
    public function create()
    {
        $fees = FeeStructure::all();
        return view('student.complaints.create', compact('fees'));
    }

    /**
     * Store a newly created payment complaint in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'fee_structure_id' => 'required|exists:fee_structures,id',
            'transaction_ref' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
        ]);

        $student = \App\Models\Student::where('user_id', Auth::id())->firstOrFail();

        PaymentComplaint::create([
            'student_id' => $student->id,
            'category' => $request->category,
            'fee_structure_id' => $request->fee_structure_id,
            'transaction_ref' => $request->transaction_ref,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        return redirect()->route('student.complaints.index')
            ->with('success', 'Your payment complaint has been submitted and is awaiting verification.');
    }
}
