<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentService
{
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

    public function updateStudentProfile(Student $student, array $data): Student
    {
        $student->update($data);
        return $student;
    }

    public function deactivateStudent(Student $student): void
    {
        $student->update(['status' => 'inactive']);
    }
}
