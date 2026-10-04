<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Applied in the original levels table migration for fresh installs.
    }

    public function down(): void
    {
        // Nothing to reverse; this migration is retained for history only.
    }
};
