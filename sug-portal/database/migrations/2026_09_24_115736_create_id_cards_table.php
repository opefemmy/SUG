<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('id_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('card_number')->unique();
            $table->string('photo_path')->nullable();
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->string('status')->default('active'); // active, blocked, expired
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('id_cards');
    }
};
