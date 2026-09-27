<?php

namespace App\Services\Elections;

use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\Candidate;
use App\Models\ElectionAuditLog;
use Illuminate\Support\Facades\DB;

class ElectionService
{
    /**
     * Create a new election.
     */
    public function createElection(array $data): Election
    {
        return Election::create($data);
    }

    /**
     * Add a position to an election.
     */
    public function addPosition(int $electionId, string $name, ?string $description = null): ElectionPosition
    {
        return ElectionPosition::create([
            'election_id' => $electionId,
            'name' => $name,
            'description' => $description,
        ]);
    }

    /**
     * Approve a candidate.
     */
    public function approveCandidate(int $candidateId): void
    {
        $candidate = Candidate::findOrFail($candidateId);
        $candidate->update(['approval_status' => 'approved']);

        $this->logAction($candidate->election_id, 'CANDIDATE_APPROVED', "Candidate {$candidate->id} was approved.");
    }

    /**
     * Log an election event for auditing.
     */
    public function logAction(int $electionId, string $action, string $description): void
    {
        ElectionAuditLog::create([
            'election_id' => $electionId,
            'action' => $action,
            'description' => $description,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
        ]);
    }
}
