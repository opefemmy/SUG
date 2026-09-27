<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\Elections\VotingService;
use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VotingController extends Controller
{
    protected $votingService;

    public function __construct(VotingService $votingService)
    {
        $this->votingService = $votingService;
    }

    /**
     * List all live elections.
     */
    public function index()
    {
        $elections = Election::where('status', 'Open')
            ->get()
            ->filter(function($election) {
                return $election->isLive();
            });

        $view = Auth::user()->hasRole('admin')
            ? 'admin.elections.index'
            : 'student.elections.index';

        return view($view, compact('elections'));
    }

    /**
     * Display the voting page for an active election.
     */
    public function show(Election $election)
    {
        // Only show if election is live
        if (!$election->isLive()) {
            return redirect()->route('student.dashboard')->with('error', 'This election is not currently open.');
        }

        // Check if student has already voted for any position in this election
        $student = Auth::user()->student;
        // Since we use voter_hash, we can't easily query "did this user vote" without the hash.
        // However, the VotingService handles this. We can notify the user in the store method.

        // Load positions and their approved candidates
        $positions = ElectionPosition::where('election_id', $election->id)
            ->with(['candidates' => function($query) {
                $query->where('approval_status', 'approved');
            }])
            ->get();

        return view('student.elections.vote', compact('election', 'positions'));
    }

    /**
     * Handle the vote submission.
     */
    public function store(Request $request, Election $election)
    {
        $request->validate([
            'votes' => 'required|array',
            'votes.*' => 'required|integer|exists:candidates,id',
        ]);

        $student = Auth::user()->student; // Assuming User has a student relationship

        try {
            foreach ($request->votes as $positionId => $candidateId) {
                $this->votingService->castVote(
                    $student,
                    $election->id,
                    (int)$positionId,
                    (int)$candidateId
                );
            }

            return redirect()->route('student.elections.index')
                ->with('success', 'Your votes have been cast successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
