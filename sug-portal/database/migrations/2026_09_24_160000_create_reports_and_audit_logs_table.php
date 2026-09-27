<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('event'); // e.g., 'user.login', 'payment.update', 'election.vote'
            $table->string('model_type')->nullable(); // e.g., 'App\Models\Payment'
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
        });

        Schema::create('institutional_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_type'); // e.g., 'Financial', 'Academic', 'Election'
            $table->string('title');
            $table->json('parameters'); // The filters used to generate the report
            $table->string('file_path'); // Path to the generated PDF/CSV
            $table->foreignId('generated_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutional_reports');
        Schema::dropIfExists('system_audit_logs');
    }
};
