<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentBiodata;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class BiodataController extends Controller
{
    public function index()
    {
        $studentId = Auth::user()->id;
        $student = \App\Models\Student::where('user_id', $studentId)->firstOrFail();
        $biodata = \App\Models\StudentBiodata::where('student_id', $student->id)->first();

        $schools = \App\Models\School::all();

        return view('student.biodata.index', compact('biodata', 'student', 'schools'));
    }

    public function getDepartments(Request $request)
    {
        $request->validate(['school_id' => 'required|exists:schools,id']);
        $departments = \App\Models\Department::where('school_id', $request->school_id)->get();
        return response()->json($departments);
    }

    public function getProgrammes(Request $request)
    {
        $request->validate(['department_id' => 'required|exists:departments,id']);
        $programmes = \App\Models\Programme::where('department_id', $request->department_id)->get();
        return response()->json($programmes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:student_biodata,email,' . (Auth::user()->id ? 'student_id,' . \App\Models\Student::where('user_id', Auth::id())->value('id') : ''),
            'phone_number' => 'required|string',
            'house_address' => 'required|string',
            'parent_name' => 'required|string',
            'parent_phone' => 'required|string',
            'parent_email' => 'required|email',
            'school_id' => 'required|exists:schools,id',
            'department_id' => 'required|exists:departments,id',
            'programme_id' => 'required|exists:programmes,id',
            'passport' => 'nullable|image|max:2048',
        ]);

        $student = \App\Models\Student::where('user_id', Auth::id())->firstOrFail();

        $data = $request->only([
            'first_name', 'last_name', 'middle_name', 'email',
            'phone_number', 'house_address', 'parent_name',
            'parent_phone', 'parent_email'
        ]);

        if ($request->hasFile('passport')) {
            $path = $request->file('passport')->store('passports', 'public');
            $data['passport_path'] = $path;
        }

        // Update student academic mapping
        $student->update([
            'school_id' => $request->school_id,
            'department_id' => $request->department_id,
            'programme_id' => $request->programme_id,
        ]);

        $biodata = \App\Models\StudentBiodata::updateOrCreate(
            ['student_id' => $student->id],
            array_merge($data, ['is_completed' => true])
        );

        return redirect()->route('student.auth.change_password')->with('success', 'Biodata updated successfully. Now, please change your password for security.');
    }

    public function changePassword()
    {
        return view('student.auth.change_password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('student.dashboard')->with('success', 'Password changed successfully!');
    }
}
