<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Elections\VotingService;
use App\Models\Election;
use Illuminate\Http\Request;

class ElectionResultsController extends Controller
{
    protected $votingService;

    public function __construct(VotingService $votingService)
    {
        $this->votingService = $votingService;
    }

    /**
     * View results for a specific election.
     */
    public function show(Election $election)
    {
        // Only admins can view results, and typically only after the election is Closed
        if ($election->status !== 'Closed') {
            return redirect()->route('admin.elections.index')
                ->with('info', 'Results are only available after the election has been closed.');
        }

        $results = $this->votingService->calculateResults($election->id);

        return view('admin.elections.results', compact('election', 'results'));
    }

    /**
     * Publish the results to the public dashboard.
     */
    public function publish(Request $request, Election $election)
    {
        $election->update(['status' => 'Published']);

        return back()->with('success', 'Election results have been published to the public.');
    }
}
