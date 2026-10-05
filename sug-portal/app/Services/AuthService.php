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
        $email = $credentials['email'];
        $password = $credentials['password'];

        // 1. Try standard authentication
        if (Auth::attempt($credentials)) {
            return $this->validateUserStatus(Auth::user());
        }

        // 2. Fallback for first-time login: check if provided password matches the user's last_name (case-insensitive)
        $user = User::where('email', $email)->first();
        if ($user && $user->hasRole('student')) {
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
            'email' => [__('auth.failed')],
        ]);
    }

    private function validateUserStatus(User $user)
    {
        if ($user->status !== 'active') {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => ['Your account is inactive. Please contact the administrator.'],
            ]);
        }
        return $user;
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
                $student = \App\Models\Student::create([
                    'user_id' => $user->id,
                    'matric_no' => $data['matric_no'],
                    'department_id' => $data['department_id'],
                    'current_level_id' => $data['level_id'],
                ]);

                \App\Models\StudentBiodata::create([
                    'student_id' => $student->id,
                    'first_name' => explode(' ', $data['name'])[0] ?? 'Student',
                    'last_name' => count(explode(' ', $data['name'])) > 1 ? end(explode(' ', $data['name'])) : 'Student',
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
