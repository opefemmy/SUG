<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voter_eligibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->boolean('is_eligible')->default(true);
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->unique(['election_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voter_eligibilities');
    }
};
