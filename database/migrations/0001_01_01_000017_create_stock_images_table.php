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
        Schema::create('stock_images', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['Tour', 'Vehicle', 'HeroBanner', 'General']);
            $table->string('title');
            $table->string('image_url');
            $table->string('alt_text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_images');
    }
};
