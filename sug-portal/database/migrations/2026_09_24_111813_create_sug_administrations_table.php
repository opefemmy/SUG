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
        Schema::create('sug_administrations', function (Blueprint $table) {
            $table->id();
            $table->string('term_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('past'); // current, past
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sug_administrations');
    }
};
