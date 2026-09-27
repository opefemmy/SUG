<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->onDelete('cascade');
            $table->foreignId('position_id')->constrained('election_positions')->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained('candidates')->onDelete('cascade');
            $table->string('voter_hash')->index(); // Hashed identifier to protect voter privacy
            $table->timestamps();

            // Ensure a voter can only vote once per position in a specific election
            $table->unique(['election_id', 'position_id', 'voter_hash'], 'unique_vote_per_position');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
