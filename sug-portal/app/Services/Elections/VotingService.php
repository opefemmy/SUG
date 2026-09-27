<?php

namespace App\Services\Elections;

use App\Models\Vote;
use App\Models\Election;
use App\Models\VoterEligibility;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;

class VotingService
{
    /**
     * Cast a vote for a specific position.
     *
     * This method implements strict security to prevent double voting
     * and protects voter privacy using a hash.
     */
    public function castVote(Student $student, int $electionId, int $positionId, int $candidateId): bool
    {
        return DB::transaction(function () use ($student, $electionId, $positionId, $candidateId) {
            $election = Election::findOrFail($electionId);

            // 1. Check if election is currently Open and within date range
            if (!$election->isLive()) {
                throw new Exception("This election is not currently open for voting.");
            }

            // 2. Verify Voter Eligibility
            $eligibility = VoterEligibility::where('election_id', $electionId)
                ->where('student_id', $student->id)
                ->first();

            if (!$eligibility || !$eligibility->is_eligible) {
                throw new Exception("You are not eligible to vote in this election.");
            }

            // 3. Protect Privacy & Prevent Double Voting
            // We create a unique hash for this student + election + position.
            // This allows us to track IF they voted without storing WHO they voted for.
            $voterHash = Hash::make($student->id . $electionId . $positionId . config('app.key'));

            if (Vote::where('election_id', $electionId)
                ->where('position_id', $positionId)
                ->where('voter_hash', $voterHash)
                ->exists()) {
                throw new Exception("You have already cast a vote for this position.");
            }

            // 4. Record the Vote
            Vote::create([
                'election_id' => $electionId,
                'position_id' => $positionId,
                'candidate_id' => $candidateId,
                'voter_hash' => $voterHash,
            ]);

            return true;
        });
    }

    /**
     * Calculate final results for an election.
     */
    public function calculateResults(int $electionId): array
    {
        $results = [];
        $positions = \App\Models\ElectionPosition::where('election_id', $electionId)->get();

        foreach ($positions as $position) {
            $votes = Vote::where('election_id', $electionId)
                ->where('position_id', $position->id)
                ->select('candidate_id', DB::raw('count(*) as total'))
                ->groupBy('candidate_id')
                ->get();

            $results[$position->id] = $votes;
        }

        return $results;
    }
}
