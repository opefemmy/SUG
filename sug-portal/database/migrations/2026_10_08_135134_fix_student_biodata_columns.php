<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_biodata', function (Blueprint $table) {
            // Add missing columns required by the import service
            if (!Schema::hasColumn('student_biodata', 'first_name')) {
                $table->string('first_name')->after('student_id');
            }
            if (!Schema::hasColumn('student_biodata', 'last_name')) {
                $table->string('last_name')->after('first_name');
            }
            if (!Schema::hasColumn('student_biodata', 'middle_name')) {
                $table->string('middle_name')->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('student_biodata', 'email')) {
                $table->string('email')->unique()->after('middle_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_biodata', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'middle_name', 'email']);
        });
    }
};
