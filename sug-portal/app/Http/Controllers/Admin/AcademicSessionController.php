<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use Illuminate\Http\Request;

class AcademicSessionController extends Controller
{
    public function index()
    {
        $sessions = AcademicSession::latest()->paginate(15);
        return view('admin.academic.sessions.index', compact('sessions'));
    }

    public function create()
    {
        return view('admin.academic.sessions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'session_name' => 'required|string|max:255|unique:academic_sessions,session_name',
            'is_current' => 'boolean',
        ]);

        if ($request->boolean('is_current')) {
            AcademicSession::where('is_current', true)->update(['is_current' => false]);
        }

        AcademicSession::create($validated);

        return redirect()->route('admin.academic.sessions.index')->with('success', 'Academic session created successfully.');
    }

    public function edit(AcademicSession $academicSession)
    {
        return view('admin.academic.sessions.edit', compact('academicSession'));
    }

    public function update(Request $request, AcademicSession $academicSession)
    {
        $validated = $request->validate([
            'session_name' => 'required|string|max:255|unique:academic_sessions,session_name,' . $academicSession->id,
            'is_current' => 'boolean',
        ]);

        if ($request->boolean('is_current')) {
            AcademicSession::where('is_current', true)->update(['is_current' => false]);
        }

        $academicSession->update($validated);

        return redirect()->route('admin.academic.sessions.index')->with('success', 'Academic session updated successfully.');
    }

    public function destroy(AcademicSession $academicSession)
    {
        $academicSession->delete();
        return redirect()->route('admin.academic.sessions.index')->with('success', 'Academic session deleted successfully.');
    }
}
