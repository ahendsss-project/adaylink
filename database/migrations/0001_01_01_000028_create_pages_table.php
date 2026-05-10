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
        if (! Schema::hasTable('pages')) {
            Schema::create('pages', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('website_id');
                $table->string('title');
                $table->string('slug');
                $table->text('content')->nullable();
                $table->enum('type', ['about', 'contact', 'terms', 'services', 'custom'])->default('custom');
                $table->boolean('is_published')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();

                $table->foreign('website_id')->references('id')->on('websites')->cascadeOnDelete();
                $table->unique(['website_id', 'slug']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
