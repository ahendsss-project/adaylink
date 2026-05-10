<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Phase 5: Add features JSON to subscription_plans + create reviews table.
     */
    public function up(): void
    {
        // Add features JSON column to subscription_plans (idempotent)
        if (!Schema::hasColumn('subscription_plans', 'features')) {
            Schema::table('subscription_plans', function (Blueprint $table) {
                $table->json('features')->nullable()->after('is_active')->comment('Feature toggles: floating_whatsapp, social_share, gallery_lightbox, reviews');
            });
        }

        // Create reviews table (idempotent) — websites uses UUID PK
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->uuid('website_id');
                $table->string('reviewer_name');
                $table->string('reviewer_email')->nullable();
                $table->unsignedTinyInteger('rating')->default(5)->comment('1-5 stars');
                $table->text('comment')->nullable();
                $table->boolean('is_approved')->default(false);
                $table->timestamps();

                $table->foreign('website_id')->references('id')->on('websites')->cascadeOnDelete();
                $table->index('is_approved');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');

        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('features');
        });
    }
};
