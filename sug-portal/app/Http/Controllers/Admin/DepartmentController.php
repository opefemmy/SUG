<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::with('school');

        if ($request->has('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        $departments = $query->get();
        $schools = School::all();

        return view('admin.academic.departments.index', compact('departments', 'schools'));
    }

    public function create()
    {
        $schools = School::all();
        return view('admin.academic.departments.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Department::create($validated);

        return redirect()->route('admin.academic.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        $schools = School::all();
        return view('admin.academic.departments.edit', compact('department', 'schools'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $department->update($validated);

        return redirect()->route('admin.academic.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        if ($department->programmes()->exists()) {
            return back()->with('error', 'Cannot delete department with existing programmes.');
        }

        $department->delete();

        return redirect()->route('admin.academic.departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
