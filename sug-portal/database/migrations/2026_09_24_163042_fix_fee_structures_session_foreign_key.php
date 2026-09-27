<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite does not support dropping foreign keys easily.
        // The safest way to fix this in SQLite is to recreate the table with correct constraints.

        // 1. Backup data
        $payments = DB::table('fee_structures')->get();

        // 2. Disable foreign keys during the move
        DB::statement('PRAGMA foreign_keys = OFF');

        // 3. Drop the existing table
        Schema::dropIfExists('fee_structures');

        // 4. Recreate table with correct foreign key for academic_sessions
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('programme_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('level_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('session_id')->constrained('academic_sessions')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->boolean('is_mandatory')->default(true);
            $table->timestamps();
        });

        // 5. Restore data
        foreach ($payments as $payment) {
            DB::table('fee_structures')->insert([
                'id' => $payment->id,
                'fee_type_id' => $payment->fee_type_id,
                'programme_id' => $payment->programme_id,
                'level_id' => $payment->level_id,
                'session_id' => $payment->session_id,
                'amount' => $payment->amount,
                'is_mandatory' => $payment->is_mandatory,
                'created_at' => $payment->created_at,
                'updated_at' => $payment->updated_at,
            ]);
        }

        DB::statement('PRAGMA foreign_keys = ON');
    }

    public function down(): void
    {
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('programme_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('level_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('session_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->boolean('is_mandatory')->default(true);
            $table->timestamps();
        });
    }
};
