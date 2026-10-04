<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Applied in the original student biodata migration for fresh installs.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_biodata', function (Blueprint $table) {
            // SQLite does not support dropping multiple columns easily
            // In a real scenario, we might recreate the table
        });
    }
};
