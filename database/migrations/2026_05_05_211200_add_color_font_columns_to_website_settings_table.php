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
        Schema::table('website_settings', function (Blueprint $table) {
            $table->string('secondary_color')->nullable()->after('primary_color');
            $table->string('font_heading')->nullable()->after('font_family');
            $table->string('font_body')->nullable()->after('font_heading');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn(['secondary_color', 'font_heading', 'font_body']);
        });
    }
};
