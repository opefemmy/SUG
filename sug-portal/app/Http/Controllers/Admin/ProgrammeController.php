<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Programme;
use App\Models\Department;
use App\Services\AcademicImportService;
use Illuminate\Http\Request;

class ProgrammeController extends Controller
{
    protected $importService;

    public function __construct(AcademicImportService $importService)
    {
        $this->importService = $importService;
    }

    public function index(Request $request)
    {
        $query = Programme::with('department');

        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $programmes = $query->get();
        $departments = Department::with('school')->get();

        return view('admin.academic.programmes.index', compact('programmes', 'departments'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('admin.academic.programmes.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'duration_years' => 'required|integer|min:1',
        ]);

        Programme::create($validated);

        return redirect()->route('admin.academic.programmes.index')
            ->with('success', 'Programme created successfully.');
    }

    public function edit(Programme $programme)
    {
        $departments = Department::all();
        return view('admin.academic.programmes.edit', compact('programme', 'departments'));
    }

    public function update(Request $request, Programme $programme)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'duration_years' => 'required|integer|min:1',
        ]);

        $programme->update($validated);

        return redirect()->route('admin.academic.programmes.index')
            ->with('success', 'Programme updated successfully.');
    }

    public function destroy(Programme $programme)
    {
        $programme->delete();

        return redirect()->route('admin.academic.programmes.index')
            ->with('success', 'Programme deleted successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $results = $this->importService->importHierarchy($path);

        return back()->with('success', "Successfully imported {$results['success']} academic records.")
                     ->with('errors', $results['errors']);
    }

    public function downloadTemplate()
    {
        return response()->download(storage_path('templates/academic_hierarchy_template.csv'), 'academic_hierarchy_template.csv');
    }
}
