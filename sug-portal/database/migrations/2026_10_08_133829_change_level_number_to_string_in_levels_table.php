<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fresh databases already use a string column in the original migration.
        // Existing production databases still need the legacy integer converted.
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE levels MODIFY level_number VARCHAR(255) NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE levels MODIFY level_number INT NOT NULL');
        }
    }
};
