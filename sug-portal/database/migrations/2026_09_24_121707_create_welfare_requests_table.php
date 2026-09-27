<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('welfare_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('welfare_categories')->onDelete('cascade');
            $table->string('subject');
            $table->text('description');
            $table->string('priority')->default('medium'); // low, medium, high, emergency
            $table->string('status')->default('submitted'); // submitted, under_review, approved, rejected, resolved, closed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('welfare_requests');
    }
};
