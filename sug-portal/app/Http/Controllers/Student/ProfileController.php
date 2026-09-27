<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the student's profile information.
     */
    public function index()
    {
        // Retrieve the student profile linked to the authenticated user
        $student = Student::with('biodata')->where('user_id', Auth::id())->firstOrFail();

        return view('student.profile', [
            'student' => $student
        ]);
    }
}
