<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Fix website_id column in reviews table from bigint to uuid,
     * matching the UUID primary key used by the websites table.
     */
    public function up(): void
    {
        // Drop foreign key if it exists
        $foreignKeys = $this->getForeignKeys('reviews', 'website_id');
        if (!empty($foreignKeys)) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropForeign(['website_id']);
            });
        }

        // Drop and recreate the column as uuid
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('website_id');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->uuid('website_id');

            $table->foreign('website_id')
                ->references('id')
                ->on('websites')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['website_id']);
            $table->dropColumn('website_id');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('website_id')
                ->constrained('websites')
                ->cascadeOnDelete();
        });
    }

    /**
     * Get the foreign key constraint names for a given column.
     */
    private function getForeignKeys(string $table, string $column): array
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            $rows = DB::select("PRAGMA foreign_key_list('{$table}')");
            return array_filter($rows, fn ($row) => $row->from === $column);
        }

        // MySQL
        $database = Schema::getConnection()->getDatabaseName();
        $rows = DB::select(
            "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?
             AND REFERENCED_TABLE_NAME IS NOT NULL",
            [$database, $table, $column]
        );

        return $rows;
    }
};
