<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Phase 3: Add folder_name, thumbnail_url, is_active to templates table.
     * The tier column already exists from Phase 2 migration.
     */
    public function up(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->string('folder_name')->nullable()->after('tier')->comment('Physical folder name in resources/views/templates/');
            $table->string('thumbnail_url')->nullable()->after('folder_name')->comment('Preview image URL');
            $table->boolean('is_active')->default(true)->after('thumbnail_url')->comment('Control whether template is available');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn(['folder_name', 'thumbnail_url', 'is_active']);
        });
    }
};
