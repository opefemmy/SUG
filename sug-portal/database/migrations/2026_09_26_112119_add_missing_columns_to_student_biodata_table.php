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
        Schema::table('student_biodata', function (Blueprint $table) {
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
            if (!Schema::hasColumn('student_biodata', 'phone_number')) {
                $table->string('phone_number')->after('email');
            }
            if (!Schema::hasColumn('student_biodata', 'house_address')) {
                $table->string('house_address')->after('phone_number');
            }
            if (!Schema::hasColumn('student_biodata', 'parent_name')) {
                $table->string('parent_name')->after('house_address');
            }
            if (!Schema::hasColumn('student_biodata', 'parent_phone')) {
                $table->string('parent_phone')->after('parent_name');
            }
            if (!Schema::hasColumn('student_biodata', 'parent_email')) {
                $table->string('parent_email')->after('parent_phone');
            }
            if (!Schema::hasColumn('student_biodata', 'passport_path')) {
                $table->string('passport_path')->nullable()->after('parent_email');
            }
            if (!Schema::hasColumn('student_biodata', 'is_completed')) {
                $table->boolean('is_completed')->default(false)->after('passport_path');
            }
        });
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
