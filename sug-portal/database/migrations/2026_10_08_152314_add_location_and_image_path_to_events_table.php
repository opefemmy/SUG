<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $columns = $this->eventColumns();

        Schema::table('events', function (Blueprint $table) use ($columns) {
            if (! in_array('location', $columns, true)) {
                $table->string('location')->nullable();
            }

            if (! in_array('image_path', $columns, true)) {
                $table->string('image_path')->nullable();
            }

            if (! in_array('sort_order', $columns, true)) {
                $table->integer('sort_order')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $existing = $this->eventColumns();

        Schema::table('events', function (Blueprint $table) use ($existing) {
            $columns = array_filter(
                ['location', 'image_path', 'sort_order'],
                fn (string $column) => in_array($column, $existing, true)
            );

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }

    private function eventColumns(): array
    {
        if (DB::getDriverName() === 'sqlite') {
            return array_map(
                fn (object $column) => $column->name,
                DB::select("PRAGMA table_info('events')")
            );
        }

        return Schema::getColumnListing('events');
    }
};
