<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use App\Models\Level;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StudentService
{
    /**
     * Level mapping for automatic promotion.
     * Normalized level name => Next level name.
     */
    private const PROMOTION_MAP = [
        'ND I'   => 'ND II',
        'HND I'  => 'HND II',
    ];

    /**
     * Enroll a new student.
     */
    public function enrollStudent(array $userData, array $studentData): Student
    {
        return DB::transaction(function () use ($userData, $studentData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'status' => 'active',
            ]);

            return Student::create(array_merge($studentData, ['user_id' => $user->id]));
        });
    }

    /**
     * Update student profile and handle automatic promotion if session changes.
     */
    public function updateStudentProfile(Student $student, array $data): Student
    {
        $oldSessionId = $student->session_id;
        $newSessionId = $data['session_id'] ?? $oldSessionId;

        // Update student record
        $student->update($data);

        // Trigger automatic promotion if session has changed and no manual level override was provided
        if ($oldSessionId !== $newSessionId && !isset($data['current_level_id'])) {
            $this->handleAutomaticPromotion($student);
        }

        return $student;
    }

    /**
     * Internal logic to promote a student to the next level.
     */
    protected function handleAutomaticPromotion(Student $student): void
    {
        try {
            $currentLevel = $student->level;
            if (!$currentLevel) {
                return;
            }

            $currentLevelName = strtoupper(trim($currentLevel->level_number));
            $nextLevelName = self::PROMOTION_MAP[$currentLevelName] ?? null;

            if ($nextLevelName) {
                // Find the Level record for the same programme with the mapped next level name
                $nextLevel = Level::where('programme_id', $student->programme_id)
                    ->where('level_number', $nextLevelName)
                    ->first();

                if ($nextLevel) {
                    $student->update(['current_level_id' => $nextLevel->id]);
                    Log::info("Student {$student->id} automatically promoted from {$currentLevelName} to {$nextLevelName}");
                } else {
                    Log::warning("Automatic promotion failed: Next level {$nextLevelName} not found for programme {$student->programme_id}");
                }
            }
        } catch (\Exception $e) {
            Log::error("Error during automatic student promotion for student {$student->id}: " . $e->getMessage());
        }
    }

    /**
     * Bulk promote students from one session to another.
     */
    public function bulkPromoteStudents(int $sourceSessionId, int $destinationSessionId): array
    {
        $promotedCount = 0;
        $failuresCount = 0;

        DB::transaction(function () use ($sourceSessionId, $destinationSessionId, &$promotedCount, &$failuresCount) {
            Student::where('session_id', $sourceSessionId)
                ->chunkById(100, function ($students) use ($destinationSessionId, &$promotedCount, &$failuresCount) {
                    foreach ($students as $student) {
                        try {
                            // Update session
                            $student->update(['session_id' => $destinationSessionId]);

                            // Apply promotion logic
                            $this->handleAutomaticPromotion($student);

                            $promotedCount++;
                        } catch (\Exception $e) {
                            Log::error("Failed to bulk promote student {$student->id}: " . $e->getMessage());
                            $failuresCount++;
                        }
                    }
                });
        });

        return [
            'promoted' => $promotedCount,
            'failures' => $failuresCount,
        ];
    }

    public function deactivateStudent(Student $student): void
    {
        $student->update(['status' => 'inactive']);
    }
}
