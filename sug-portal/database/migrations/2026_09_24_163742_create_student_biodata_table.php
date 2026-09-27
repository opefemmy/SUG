<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_biodata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->unique()->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('house_address');
            $table->string('parent_name');
            $table->string('parent_phone');
            $table->string('parent_email');
            $table->string('passport_path')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_biodata');
    }
};
