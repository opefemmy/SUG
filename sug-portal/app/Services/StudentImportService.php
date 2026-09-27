<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use App\Models\Level;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;

class StudentImportService
{
    /**
     * Process a CSV file and create Student and User records.
     * Expected CSV columns: matric_no, surname, name, department, level
     */
    public function importStudents(array $rows): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => []
        ];

        foreach ($rows as $index => $row) {
            try {
                DB::transaction(function () use ($row) {
                    // Use matric_no as the username (stored in 'name' for auth simplicity or custom field)
                    // We use matric_no as the email for authentication since Laravel's default Auth uses email
                    // In a real scenario, we might override the Auth provider to use 'username'
                    $username = $row['matric_no'];
                    $password = $row['surname'];

                    // 1. Create User
                    // We use matric_no as the email field to allow login with matric number
                    $user = User::updateOrCreate(
                        ['email' => $username],
                        [
                            'name' => ($row['name'] ?? 'Student') . ' ' . ($row['surname'] ?? ''),
                            'password' => Hash::make($password),
                        ]
                    );

                    // Assign student role
                    $user->assignRole('student');

                    // 2. Resolve Department and Level
                    $dept = Department::where('name', 'LIKE', "%{$row['department']}%")->first();
                    $level = Level::where('level_number', 'LIKE', "%{$row['level']}%")->first();

                    if (!$level) {
                        throw new Exception("Invalid Level ({$row['level']})");
                    }

                    // 3. Create Student Profile
                    $school = \App\Models\School::first();
                    $defaultDept = \App\Models\Department::first();
                    $defaultProg = \App\Models\Programme::first();
                    $currentSession = \App\Models\AcademicSession::where('is_current', true)->first() ?? \App\Models\AcademicSession::first();
                    // Extract admission year from matric_no (e.g., CHT/2026/EHT/002 -> 2026)
                    $matricParts = explode('/', $username);
                    $admissionYear = (isset($matricParts[1]) && is_numeric($matricParts[1]))
                        ? (int)$matricParts[1]
                        : now()->year;

                    Student::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'matric_no' => $username,
                            'school_id' => $school ? $school->id : null,
                            'department_id' => $dept ? $dept->id : ($defaultDept ? $defaultDept->id : null),
                            'programme_id' => $defaultProg ? $defaultProg->id : null,
                            'session_id' => $currentSession ? $currentSession->id : null,
                            'current_level_id' => $level->id,
                            'admission_year' => $admissionYear,
                        ]
                    );
                });
                $results['success']++;
            } catch (Exception $e) {
                $results['failed']++;
                $results['errors'][] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        return $results;
    }
}
