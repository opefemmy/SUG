<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Payment;
use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\Candidate;
use App\Models\VoterEligibility;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SUGPortalEndToEndTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the complete journey: Student Registration -> Fee Payment -> Election Voting.
     */
    public function test_complete_student_lifecycle()
    {
        // 1. Setup Academic Structure (Simplified for test)
        // Assume seeders have handled the levels/departments

        // 2. Create Student and User
        $user = User::factory()->create(['role' => 'student']);
        $student = Student::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        // 3. Test Fee Payment Flow
        $payment = Payment::factory()->create([
            'student_id' => $student->id,
            'status' => 'pending',
            'amount' => 5000
        ]);

        $response = $this->get(route('student.receipts'));
        $response->assertStatus(200);
        $response->assertSee($payment->receipt_no);

        // 4. Test Election Journey
        $election = Election::factory()->create(['status' => 'Open']);
        $position = ElectionPosition::factory()->create(['election_id' => $election->id]);
        $candidate = Candidate::factory()->create([
            'election_id' => $election->id,
            'position_id' => $position->id,
            'approval_status' => 'approved'
        ]);

        VoterEligibility::create([
            'election_id' => $election->id,
            'student_id' => $student->id,
            'is_eligible' => true
        ]);

        // Cast Vote
        $response = $this->post(route('student.elections.vote.store', $election), [
            'votes' => [
                $position->id => $candidate->id
            ]
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('votes', [
            'election_id' => $election->id,
            'candidate_id' => $candidate->id
        ]);

        // Attempt double vote (Should Fail)
        $response = $this->post(route('student.elections.vote.store', $election), [
            'votes' => [
                $position->id => $candidate->id
            ]
        ]);
        $response->assertSessionHasErrors('error');
    }

    /**
     * Test Administrative Access and Security.
     */
    public function test_admin_security_and_reporting()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student']);

        // Student should NOT access admin dashboard
        $this->actingAs($student);
        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(403);

        // Admin should access results
        $this->actingAs($admin);
        $election = Election::factory()->create(['status' => 'Closed']);

        $response = $this->get(route('admin.elections.results', $election));
        $response->assertStatus(200);
    }
}
