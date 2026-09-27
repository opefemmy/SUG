<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\AcademicSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ElectionController extends Controller
{
    public function index()
    {
        $elections = Election::with('positions')->get();
        return view('admin.elections.index', compact('elections'));
    }

    public function create()
    {
        $sessions = AcademicSession::all();
        return view('admin.elections.create', compact('sessions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'session_id' => 'required|exists:academic_sessions,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:Draft,Scheduled,Open,Closed,Published',
        ]);

        Election::create($validated);

        return redirect()->route('admin.elections.index')
            ->with('success', 'Election created successfully.');
    }

    public function show(Election $election)
    {
        $election = $election->load(['positions.candidates']);
        return view('admin.elections.show', compact('election'));
    }

    public function edit(Election $election)
    {
        $sessions = AcademicSession::all();
        return view('admin.elections.edit', compact('election', 'sessions'));
    }

    public function update(Request $request, Election $election)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'session_id' => 'required|exists:academic_sessions,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:Draft,Scheduled,Open,Closed,Published',
        ]);

        $election->update($validated);

        return redirect()->route('admin.elections.index')
            ->with('success', 'Election updated successfully.');
    }

    public function destroy(Election $election)
    {
        // Prevent deleting elections that already have votes
        if ($election->votes()->exists()) {
            return back()->with('error', 'Cannot delete an election that already has votes.');
        }

        $election->delete();

        return redirect()->route('admin.elections.index')
            ->with('success', 'Election deleted successfully.');
    }
}
