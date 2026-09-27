<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('matric_no')->unique();
            $table->foreignId('school_id')->constrained();
            $table->foreignId('department_id')->constrained();
            $table->foreignId('programme_id')->constrained();
            $table->foreignId('current_level_id')->constrained('levels');
            $table->foreignId('session_id')->constrained('academic_sessions');
            $table->year('admission_year');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
