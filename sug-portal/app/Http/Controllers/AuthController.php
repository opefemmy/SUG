<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLogin(): \Illuminate\Contracts\View\View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required'],
        ]);

        try {
            $user = $this->authService->login($request->only('login', 'password'));
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        if ($user->hasRole('student')) {
            return redirect()->intended(route('student.dashboard'));
        }

        return redirect()->intended(route('admin.dashboard'));
    }

    public function showRegister(): \Illuminate\Contracts\View\View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:student,admin'],
        ];

        if ($request->role === 'student') {
            $rules['matric_no'] = ['required', 'string', 'unique:students,matric_no'];
            $rules['school_id'] = ['required', 'exists:schools,id'];
            $rules['department_id'] = ['required', 'exists:departments,id'];
            $rules['programme_id'] = ['required', 'exists:programmes,id'];
            $rules['level_id'] = ['required', 'exists:levels,id'];
            $rules['session_id'] = ['required', 'exists:academic_sessions,id'];
            $rules['admission_year'] = ['required', 'integer', 'between:1900,'.date('Y')];
        }

        $request->validate($rules);

        try {
            $this->authService->register($request->all());
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()])->withInput();
        }

        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->authService->logout();
        return redirect()->route('home')->with('logout_message', 'Thank you! Hope to see you later.');
    }
}
