<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Student;
use App\Models\VoterEligibility;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ElectionAccreditationController extends Controller
{
    /**
     * Export a list of students who have NOT been accredited for a specific election.
     */
    public function exportUnaccredited(Election $election)
    {
        // Ensure accreditation has ended or voting has started
        if ($election->accreditation_end && now()->lt($election->accreditation_end)) {
            return back()->with('error', 'Accreditation period has not yet ended.');
        }

        $filename = "unaccredited_students_{$election->id}_" . now()->format('Ymd_His') . ".csv";

        $response = response()->streamDownload(function () use ($election) {
            $handle = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($handle, ['Matric Number', 'Full Name', 'Department', 'Programme', 'Level']);

            // Get all students who are eligible but not yet accredited
            // We look for students who either have no record in voter_eligibilities
            // or have a record where is_accredited is false.
            $students = Student::with(['department', 'programme', 'level'])
                ->whereNotExists(function ($query) use ($election) {
                    $query->select(\DB::raw(1))
                        ->from('voter_eligibilities')
                        ->whereColumn('voter_eligibilities.student_id', 'students.id')
                        ->where('voter_eligibilities.election_id', $election->id)
                        ->where('voter_eligibilities.is_accredited', true);
                })
                ->get();

            foreach ($students as $student) {
                fputcsv($handle, [
                    $student->matric_no,
                    $student->user->name ?? 'N/A',
                    $student->department->name ?? 'N/A',
                    $student->programme->name ?? 'N/A',
                    $student->level->name ?? 'N/A',
                ]);
            }

            fclose($handle);
        }, $filename);

        return $response;
    }
}
