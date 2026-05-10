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
        Schema::create('platform_configs', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->default('adaylink');
            $table->string('tagline')->nullable();
            $table->string('main_logo_url')->nullable();
            $table->string('favicon_url')->nullable();
            $table->string('admin_whatsapp')->nullable();
            $table->string('admin_email')->nullable();
            $table->json('social_links')->nullable();
            $table->boolean('maintenance_mode')->default(false);
            $table->string('google_analytics_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_configs');
    }
};
