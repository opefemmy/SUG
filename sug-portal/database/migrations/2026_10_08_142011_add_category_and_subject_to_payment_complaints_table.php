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
        Schema::table('payment_complaints', function (Blueprint $table) {
            $table->string('category')->after('student_id')->nullable();
            $table->text('subject')->after('category')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_complaints', function (Blueprint $table) {
            $table->dropColumn(['category', 'subject']);
        });
    }
};
