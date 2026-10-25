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
        Schema::table('events', function (Blueprint $table) {
            if (! Schema::hasColumn('events', 'location')) {
                $table->string('location')->nullable();
            }

            if (! Schema::hasColumn('events', 'image_path')) {
                $table->string('image_path')->nullable();
            }

            if (! Schema::hasColumn('events', 'sort_order')) {
                $table->integer('sort_order')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $columns = array_filter(
                ['location', 'image_path', 'sort_order'],
                fn (string $column) => Schema::hasColumn('events', $column)
            );

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
