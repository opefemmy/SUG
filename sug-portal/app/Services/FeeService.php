<?php

namespace App\Services;

use App\Models\FeeStructure;
use App\Models\Student;
use Illuminate\Support\Collection;

class FeeService
{
    /**
     * Calculate total fees applicable to a student.
     */
    public function calculateApplicableFees(Student $student): Collection
    {
        return FeeStructure::with('feeType')
            ->where('session_id', $student->session_id)
            ->where(function($query) use ($student) {
                $query->whereNull('programme_id') // Global fees
                      ->orWhere('programme_id', $student->programme_id)
                      ->orWhere('level_id', $student->current_level_id);
            })
            ->get();
    }

    /**
     * Calculate the total amount due for a student.
     */
    public function calculateTotalDue(Student $student): float
    {
        $fees = $this->calculateApplicableFees($student);
        return $fees->sum('amount');
    }

    /**
     * Calculate the total amount already paid by a student.
     */
    public function calculateTotalPaid(Student $student): float
    {
        return \App\Models\Payment::where('student_id', $student->id)
            ->where('status', 'success')
            ->sum('amount');
    }

    /**
     * Calculate the remaining balance for a student.
     */
    public function calculateBalance(Student $student): float
    {
        return $this->calculateTotalDue($student) - $this->calculateTotalPaid($student);
    }
}
