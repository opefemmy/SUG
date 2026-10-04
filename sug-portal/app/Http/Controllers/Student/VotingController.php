<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\Elections\VotingService;
use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VoterEligibility;

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
        // Only show if voting is enabled in settings
        if (!\App\Services\SettingsService::get('voting_enabled')) {
            return view('student.elections.disabled');
        }

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
        // Only show if voting is enabled globally
        if (!\App\Services\SettingsService::get('voting_enabled')) {
            return redirect()->route('student.dashboard')->with('error', 'Voting is currently disabled by the administrator.');
        }

        // Only show if election is live
        if (!$election->isLive()) {
            return redirect()->route('student.dashboard')->with('error', 'This election is not currently open.');
        }

        // Accreditation check
        $student = Auth::user()->student;
        $eligibility = VoterEligibility::where('election_id', $election->id)
            ->where('student_id', $student->id)
            ->first();

        if (!$eligibility || !$eligibility->is_accredited) {
            return redirect()->route('student.elections.accredit', ['election' => $election->id])
                ->with('info', 'You must be accredited before you can cast your vote.');
        }

        // Load positions and their approved candidates
        $positions = ElectionPosition::where('election_id', $election->id)
            ->with(['candidates' => function($query) {
                $query->where('approval_status', 'approved');
            }])
            ->get();

        return view('student.elections.vote', compact('election', 'positions'));
    }

    public function accredit(Election $election)
    {
        $student = Auth::user()->student;

        $eligibility = VoterEligibility::updateOrCreate(
            ['election_id' => $election->id, 'student_id' => $student->id],
            ['is_accredited' => true]
        );

        return redirect()->route('student.elections.show', $election->id)
            ->with('success', 'You have been successfully accredited for this election!');
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

        $student = Auth::user()->student;

        // Final safety check for accreditation
        $eligibility = VoterEligibility::where('election_id', $election->id)
            ->where('student_id', $student->id)
            ->first();

        if (!$eligibility || !$eligibility->is_accredited) {
            return back()->withErrors(['error' => 'You must be accredited before voting.']);
        }

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
