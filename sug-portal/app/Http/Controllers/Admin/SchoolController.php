<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Services\AcademicImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    protected $importService;

    public function __construct(AcademicImportService $importService)
    {
        $this->importService = $importService;
    }

    public function index()
    {
        $schools = School::all();
        return view('admin.academic.schools.index', compact('schools'));
    }

    public function create()
    {
        return view('admin.academic.schools.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:schools,name',
            'code' => 'required|string|max:50|unique:schools,code',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        School::create($validated);

        return redirect()->route('admin.academic.schools.index')
            ->with('success', 'School created successfully.');
    }

    public function edit(School $school)
    {
        return view('admin.academic.schools.edit', compact('school'));
    }

    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:schools,name,' . $school->id,
            'code' => 'required|string|max:50|unique:schools,code,' . $school->id,
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $school->update($validated);

        return redirect()->route('admin.academic.schools.index')
            ->with('success', 'School updated successfully.');
    }

    public function destroy(School $school)
    {
        if ($school->departments()->exists()) {
            return back()->with('error', 'Cannot delete school with existing departments.');
        }

        $school->delete();

        return redirect()->route('admin.academic.schools.index')
            ->with('success', 'School deleted successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $results = $this->importService->importSchools($path);

        return back()->with('success', "Successfully imported {$results['success']} schools.")
                     ->with('errors', $results['errors']);
    }

    public function downloadTemplate()
    {
        return response()->download(storage_path('templates/schools_template.csv'), 'schools_template.csv');
    }
}
