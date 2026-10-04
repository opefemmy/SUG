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
        // The receipts table is created by the earlier
        // 2026_09_24_113314 migration.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to reverse; this migration is retained for history only.
    }
};
