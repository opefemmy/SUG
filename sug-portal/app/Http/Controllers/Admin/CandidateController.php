<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\Student;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        $electionId = $request->query('election_id');
        $query = Candidate::with(['student', 'position']);

        if ($electionId) {
            $query->where('election_id', $electionId);
        }

        $candidates = $query->get();
        $elections = Election::all();

        return view('admin.candidates.index', compact('candidates', 'elections'));
    }

    public function create()
    {
        $elections = Election::all();
        $positions = ElectionPosition::all();
        $students = Student::all();

        return view('admin.candidates.create', compact('elections', 'positions', 'students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'election_id' => 'required|exists:elections,id',
            'position_id' => 'required|exists:election_positions,id',
            'student_id' => 'required|exists:students,id',
            'photo_path' => 'nullable|image|max:2048',
            'manifesto' => 'nullable|string',
            'biography' => 'nullable|string',
            'approval_status' => 'required|in:pending,approved,rejected',
        ]);

        if ($request->hasFile('photo_path')) {
            $validated['photo_path'] = $request->file('photo_path')->store('candidates', 'public');
        }

        Candidate::create($validated);

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate added successfully.');
    }

    public function edit(Candidate $candidate)
    {
        $elections = Election::all();
        $positions = ElectionPosition::where('election_id', $candidate->election_id)->get();
        $students = Student::all();

        return view('admin.candidates.edit', compact('candidate', 'elections', 'positions', 'students'));
    }

    public function update(Request $request, Candidate $candidate)
    {
        $validated = $request->validate([
            'election_id' => 'required|exists:elections,id',
            'position_id' => 'required|exists:election_positions,id',
            'student_id' => 'required|exists:students,id',
            'photo_path' => 'nullable|image|max:2048',
            'manifesto' => 'nullable|string',
            'biography' => 'nullable|string',
            'approval_status' => 'required|in:pending,approved,rejected',
        ]);

        if ($request->hasFile('photo_path')) {
            $validated['photo_path'] = $request->file('photo_path')->store('candidates', 'public');
        }

        $candidate->update($validated);

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate updated successfully.');
    }

    public function destroy(Candidate $candidate)
    {
        $candidate->delete();
        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate removed successfully.');
    }

    public function approve(Candidate $candidate)
    {
        $candidate->update(['approval_status' => 'approved']);
        return back()->with('success', 'Candidate approved successfully.');
    }

    public function reject(Candidate $candidate)
    {
        $candidate->update(['approval_status' => 'rejected']);
        return back()->with('error', 'Candidate rejected.');
    }
}
