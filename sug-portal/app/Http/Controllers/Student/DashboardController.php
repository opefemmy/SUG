<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\FeeStructure;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Eager load biodata to get the passport path
        $student = Student::with('biodata')->where('user_id', Auth::id())->firstOrFail();

        // FORCED BIODATA CHECK: Redirect if biodata is not completed
        if (!$student->biodata || !$student->biodata->is_completed) {
            return redirect()->route('student.biodata.index')
                ->with('warning', 'Please complete your biodata to access the dashboard.');
        }

        // Calculate total fees required for the student's session, level, and programme
        $totalFeesRequired = FeeStructure::where('session_id', $student->session_id)
            ->where(function($query) use ($student) {
                $query->where('level_id', $student->current_level_id)
                      ->orWhereNull('level_id');
            })
            ->where(function($query) use ($student) {
                $query->where('programme_id', $student->programme_id)
                      ->orWhereNull('programme_id');
            })
            ->sum('amount');

        // Calculate total successfully paid fees
        $totalPaid = \App\Models\Payment::where('student_id', $student->id)
            ->whereIn('status', ['success', 'successful', 'completed'])
            ->sum('amount');

        // The actual outstanding balance
        $outstandingBalance = max(0, $totalFeesRequired - $totalPaid);

        return view('student.dashboard', [
            'totalFees' => $outstandingBalance,
            'student' => $student,
            'isCleared' => $outstandingBalance <= 0
        ]);
    }
}
