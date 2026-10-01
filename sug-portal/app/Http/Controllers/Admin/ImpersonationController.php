<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\StudentBiodata;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ImpersonationController extends Controller
{
    /**
     * Show the master login page.
     */
    public function showLogin(): View
    {
        return view('admin.unlock.login');
    }

    /**
     * Authenticate the master admin.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $masterUser = env('MASTER_ADMIN_USERNAME');
        $masterPass = env('MASTER_ADMIN_PASSWORD');

        if ($request->username === $masterUser && $request->password === $masterPass) {
            Session::put('is_master_admin', true);
            return redirect()->route('unlock.dashboard')->with('success', 'Master access granted.');
        }

        return back()->with('error', 'Invalid master credentials.');
    }

    /**
     * Show the impersonation dashboard.
     */
    public function index(): View
    {
        return view('admin.unlock.dashboard');
    }

    /**
     * Impersonate a user based on an identifier.
     */
    public function impersonate(Request $request): RedirectResponse
    {
        $request->validate([
            'identifier' => 'required|string',
        ]);

        $identifier = $request->identifier;
        $user = null;

        // 1. Search by Email
        $user = User::where('email', $identifier)->first();

        // 2. Search by Name/Username
        if (!$user) {
            $user = User::where('name', 'like', "%{$identifier}%")->first();
        }

        // 3. Search by Matric Number (Join with Student)
        if (!$user) {
            $student = Student::where('matric_no', $identifier)->first();
            if ($student) {
                $user = $student->user;
            }
        }

        // 4. Search by Phone (Join with StudentBiodata)
        if (!$user) {
            $biodata = StudentBiodata::where('phone_number', $identifier)
                ->orWhere('whatsapp_number', $identifier)
                ->first();
            if ($biodata) {
                $user = $biodata->student->user;
            }
        }

        if (!$user) {
            return back()->with('error', 'User not found with the provided identifier.');
        }

        // Store master admin status before switching
        Session::put('is_master_admin', true);
        Session::put('impersonating', true);
        Session::put('original_admin_status', true);

        // Perform the login
        Auth::login($user);

        return redirect()->route('student.dashboard')->with('success', "Now impersonating {$user->name}");
    }

    /**
     * Stop impersonating and return to admin dashboard.
     */
    public function stop(): RedirectResponse
    {
        Auth::logout();
        Session::forget(['impersonating', 'original_admin_status']);

        // Keep the master admin session flag so they don't have to login to /unlock again
        Session::put('is_master_admin', true);

        return redirect()->route('unlock.dashboard')->with('success', 'Impersonation ended.');
    }
}
