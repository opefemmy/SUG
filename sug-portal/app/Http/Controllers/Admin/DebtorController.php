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
    protected function getDebtorsQuery(Request $request)
    {
        $query = Student::query()
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('departments', 'students.department_id', '=', 'departments.id')
            ->join('programmes', 'students.programme_id', '=', 'programmes.id')
            ->select([
                'students.id',
                'users.name',
                'students.matric_no',
                'departments.name as department_name',
                'programmes.name as programme_name',
                DB::raw("(
                    SELECT COALESCE(SUM(amount), 0)
                    FROM fee_structures
                    WHERE session_id = students.session_id
                    AND (level_id = students.current_level_id OR level_id IS NULL)
                    AND (programme_id = students.programme_id OR programme_id IS NULL)
                ) as total_required"),
                DB::raw("(
                    SELECT COALESCE(SUM(amount), 0)
                    FROM payments
                    WHERE student_id = students.id
                    AND status IN ('success', 'successful', 'completed')
                ) as total_paid")
            ])
            ->where(function($query) {
                $query->whereRaw('(SELECT COALESCE(SUM(amount), 0) FROM fee_structures WHERE session_id = students.session_id AND (level_id = students.current_level_id OR level_id IS NULL) AND (programme_id = students.programme_id OR programme_id IS NULL)) >
                (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE student_id = students.id AND status IN (\'success\', \'successful\', \'completed\'))');
            });

        if ($request->filled('school_id')) {
            $query->where('students.school_id', $request->school_id);
        }

        if ($request->filled('programme_id')) {
            $query->where('students.programme_id', $request->programme_id);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $debtors = $this->getDebtorsQuery($request)->paginate(15);

        $debtors->getCollection()->transform(function ($debtor) {
            $debtor->outstanding_balance = max(0, ($debtor->total_required ?? 0) - ($debtor->total_paid ?? 0));
            return $debtor;
        });

        $schools = \App\Models\School::orderBy('name')->get();
        $programmes = \App\Models\Programme::orderBy('name')->get();

        return view('admin.debtors.index', compact('debtors', 'schools', 'programmes'));
    }

    public function export(Request $request)
    {
        $fileName = 'debtors_list_' . now()->format('YmdHis') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, ran-expire",
            "Expires"             => "0"
        ];

        $callback = function() use ($request) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Student Name', 'Matric No', 'Department', 'Programme', 'Total Required', 'Total Paid', 'Outstanding Balance']);

            $this->getDebtorsQuery($request)->chunk(100, function ($debtors) use ($file) {
                foreach ($debtors as $debtor) {
                    $outstanding = max(0, ($debtor->total_required ?? 0) - ($debtor->total_paid ?? 0));
                    fputcsv($file, [
                        $debtor->name,
                        $debtor->matric_no,
                        $debtor->department_name,
                        $debtor->programme_name,
                        number_format($debtor->total_required, 2),
                        number_format($debtor->total_paid, 2),
                        number_format($outstanding, 2),
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
