<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Handle user login.
     */
    public function login(array $credentials): User
    {
        $login = $credentials['login'];
        $password = $credentials['password'];

        // 1. Identify the user by email or matric number
        $user = User::where('email', $login)->first();

        if (!$user) {
            $student = \App\Models\Student::where('matric_no', $login)->first();
            if ($student) {
                $user = User::find($student->user_id);
            }
        }

        if (!$user) {
            throw ValidationException::withMessages([
                'login' => [__('auth.failed')],
            ]);
        }

        // 2. Try standard password authentication
        if (Hash::check($password, $user->password)) {
            if ($this->validateUserStatus($user)) {
                Auth::login($user);
                return $user;
            }
        }

        // 3. Fallback for students: check if provided password matches the user's last_name (case-insensitive)
        if ($user->hasRole('student')) {
            $student = \App\Models\Student::where('user_id', $user->id)->first();
            $biodata = \App\Models\StudentBiodata::where('student_id', $student?->id)->first();

            if ($biodata && $biodata->last_name) {
                if (strtolower($password) === strtolower($biodata->last_name)) {
                    Auth::login($user);
                    return $this->validateUserStatus($user);
                }
            }
        }

        throw ValidationException::withMessages([
            'login' => [__('auth.failed')],
        ]);
    }

    private function validateUserStatus(User $user)
    {
        if ($user->status !== 'active') {
            Auth::logout();
            throw ValidationException::withMessages([
                'login' => ['Your account is inactive. Please contact the administrator.'],
            ]);
        }
        return true;
    }

    /**
     * Handle user registration.
     */
    public function register(array $data): User
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            // 1. Create User
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'status' => 'active',
            ]);

            // 2. Assign Role
            $user->assignRole($data['role']);

            // 3. Student Specific Records
            if ($data['role'] === 'student') {
                $nameParts = preg_split('/\s+/', trim($data['name']));
                $student = \App\Models\Student::create([
                    'user_id' => $user->id,
                    'matric_no' => $data['matric_no'],
                    'school_id' => $data['school_id'],
                    'department_id' => $data['department_id'],
                    'programme_id' => $data['programme_id'],
                    'current_level_id' => $data['level_id'],
                    'session_id' => $data['session_id'],
                    'admission_year' => $data['admission_year'],
                ]);

                \App\Models\StudentBiodata::create([
                    'student_id' => $student->id,
                    'first_name' => $nameParts[0] ?? 'Student',
                    'last_name' => count($nameParts) > 1 ? end($nameParts) : 'Student',
                    'email' => $data['email'],
                    'phone_number' => '',
                    'house_address' => '',
                    'parent_name' => '',
                    'parent_phone' => '',
                    'parent_email' => '',
                    'is_completed' => false,
                ]);
            }

            return $user;
        });
    }

    /**
     * Handle logout.
     */
    public function logout(): void
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
