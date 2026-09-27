<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Payment;
use App\Models\FeeStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DebtorController extends Controller
{
    public function index()
    {
        // Use a subquery to calculate totals for each student to avoid N+1 problem
        // and filter for only those with an outstanding balance.

        $debtors = Student::query()
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('departments', 'students.department_id', '=', 'departments.id')
            ->select([
                'students.id',
                'users.name',
                'students.matric_no',
                'departments.name as department_name',
                DB::raw("(
                    SELECT SUM(amount)
                    FROM fee_structures
                    WHERE session_id = students.session_id
                    AND (level_id = students.current_level_id OR level_id IS NULL)
                    AND (programme_id = students.programme_id OR programme_id IS NULL)
                ) as total_required"),
                DB::raw("(
                    SELECT SUM(amount)
                    FROM payments
                    WHERE student_id = students.id
                    AND status IN ('success', 'successful', 'completed')
                ) as total_paid")
            ])
            ->where(function($query) {
                $query->whereRaw('(SELECT SUM(amount) FROM fee_structures WHERE session_id = students.session_id AND (level_id = students.current_level_id OR level_id IS NULL) AND (programme_id = students.programme_id OR programme_id IS NULL)) >
                (SELECT SUM(amount) FROM payments WHERE student_id = students.id AND status IN (\'success\', \'successful\', \'completed\'))');
            })
            ->paginate(15);

        // Calculate outstanding balance for each debtor in the collection
        $debtors->getCollection()->transform(function ($debtor) {
            $debtor->outstanding_balance = max(0, ($debtor->total_required ?? 0) - ($debtor->total_paid ?? 0));
            return $debtor;
        });

        return view('admin.debtors.index', compact('debtors'));
    }
}
