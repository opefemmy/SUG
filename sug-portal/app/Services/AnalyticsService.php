<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Election;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AnalyticsService
{
    /**
     * Generate Financial Summary Analytics.
     */
    public function getFinancialAnalytics(): array
    {
        return [
            'total_revenue' => Payment::where('status', 'successful')->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'revenue_by_fee_type' => Payment::join('fee_structures', 'payments.fee_structure_id', '=', 'fee_structures.id')
                ->where('payments.status', 'successful')
                ->select('fee_structures.name', DB::raw('SUM(payments.amount) as total'))
                ->groupBy('fee_structures.name')
                ->get(),
        ];
    }

    /**
     * Generate Student Demographics Analytics.
     */
    public function getStudentAnalytics(): array
    {
        return [
            'total_students' => Student::count(),
            'students_by_level' => Student::join('levels', 'students.level_id', '=', 'levels.id')
                ->select('levels.name', DB::raw('count(*) as count'))
                ->groupBy('levels.name')
                ->get(),
            'students_by_department' => Student::join('departments', 'students.department_id', '=', 'departments.id')
                ->select('departments.name', DB::raw('count(*) as count'))
                ->groupBy('departments.name')
                ->get(),
        ];
    }

    /**
     * Generate Election Engagement Analytics.
     */
    public function getElectionAnalytics(int $electionId): array
    {
        $totalEligible = DB::table('voter_eligibilities')
            ->where('election_id', $electionId)
            ->where('is_eligible', true)
            ->count();

        $totalVotes = Vote::where('election_id', $electionId)->count();

        return [
            'turnout_percentage' => $totalEligible > 0 ? ($totalVotes / $totalEligible) * 100 : 0,
            'total_votes' => $totalVotes,
            'total_eligible' => $totalEligible,
        ];
    }

    /**
     * Generate a PDF Report.
     */
    public function generateReport(string $type, array $data, string $title): string
    {
        // Use the ReceiptService-like pattern to create a professional PDF report
        $pdf = Pdf::loadView("reports.{$type}", ['data' => $data, 'title' => $title]);

        $fileName = "report_{$type}_" . now()->format('Ymd_His') . ".pdf";
        $filePath = storage_path("app/reports/{$fileName}");

        if (!file_exists(storage_path('app/reports'))) {
            mkdir(storage_path('app/reports'), 0755, true);
        }

        $pdf->save($filePath);

        return $fileName;
    }
}
