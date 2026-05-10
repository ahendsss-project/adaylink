<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add default_locale to websites table
        Schema::table('websites', function (Blueprint $table) {
            $table->string('default_locale', 5)->default('id')->after('is_active');
        });

        // Add translations JSON to website_settings table
        Schema::table('website_settings', function (Blueprint $table) {
            $table->json('translations')->nullable()->after('gallery_images');
        });

        // Add translations JSON to tour_packages table
        Schema::table('tour_packages', function (Blueprint $table) {
            $table->json('translations')->nullable()->after('notes');
        });

        // Add translations JSON to pages table
        Schema::table('pages', function (Blueprint $table) {
            $table->json('translations')->nullable()->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn('default_locale');
        });

        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn('translations');
        });

        Schema::table('tour_packages', function (Blueprint $table) {
            $table->dropColumn('translations');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('translations');
        });
    }
};
