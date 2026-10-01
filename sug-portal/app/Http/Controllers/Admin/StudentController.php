<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $students = Student::with(['user', 'programme', 'level', 'session'])->paginate(15);
        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Fetch dependent data for the creation form
        $schools = \App\Models\School::all();
        $departments = \App\Models\Department::all();
        $programmes = \App\Models\Programme::all();
        $levels = \App\Models\Level::all();
        $sessions = \App\Models\AcademicSession::all();

        return view('admin.students.create', compact('schools', 'departments', 'programmes', 'levels', 'sessions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'matric_no' => 'required|string|unique:students,matric_no',
            'school_id' => 'required|exists:schools,id',
            'department_id' => 'required|exists:departments,id',
            'programme_id' => 'required|exists:programmes,id',
            'current_level_id' => 'required|exists:levels,id',
            'session_id' => 'required|exists:academic_sessions,id',
            'admission_year' => 'required|string',
        ]);

        try {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ];

            $studentData = [
                'matric_no' => $request->matric_no,
                'school_id' => $request->school_id,
                'department_id' => $request->department_id,
                'programme_id' => $request->programme_id,
                'current_level_id' => $request->current_level_id,
                'session_id' => $request->session_id,
                'admission_year' => $request->admission_year,
                'status' => 'active',
            ];

            $this->studentService->enrollStudent($userData, $studentData);

            return redirect()->route('admin.students.index')
                ->with('success', 'Student enrolled successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error enrolling student: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student): View
    {
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student): View
    {
        $schools = \App\Models\School::all();
        $departments = \App\Models\Department::all();
        $programmes = \App\Models\Programme::all();
        $levels = \App\Models\Level::all();
        $sessions = \App\Models\AcademicSession::all();

        return view('admin.students.edit', compact('student', 'schools', 'departments', 'programmes', 'levels', 'sessions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'school_id' => 'required|exists:schools,id',
            'department_id' => 'required|exists:departments,id',
            'programme_id' => 'required|exists:programmes,id',
            'current_level_id' => 'nullable|exists:levels,id',
            'session_id' => 'required|exists:academic_sessions,id',
        ]);

        try {
            // Update User details
            $student->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Update Student details via Service (handles automatic promotion)
            $this->studentService->updateStudentProfile($student, [
                'school_id' => $request->school_id,
                'department_id' => $request->department_id,
                'programme_id' => $request->programme_id,
                'current_level_id' => $request->current_level_id,
                'session_id' => $request->session_id,
            ]);

            return redirect()->route('admin.students.index')
                ->with('success', 'Student profile updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error updating student: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student): RedirectResponse
    {
        try {
            $student->delete();
            return redirect()->route('admin.students.index')
                ->with('success', 'Student removed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error removing student: ' . $e->getMessage());
        }
    }
}
