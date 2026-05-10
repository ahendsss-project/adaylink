<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Phase 4: Add admin control columns to users + create audit_logs table.
     */
    public function up(): void
    {
        // Add new columns to users table (idempotent)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('subscription_expires_at')->comment('Manually verified by admin');
            }
            if (!Schema::hasColumn('users', 'is_blocked')) {
                $table->boolean('is_blocked')->default(false)->after('is_verified')->comment('Instant block by admin');
            }
            if (!Schema::hasColumn('users', 'admin_note')) {
                $table->text('admin_note')->nullable()->after('is_blocked')->comment('Internal admin notes about user');
            }
        });

        // Create audit_logs table (idempotent)
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->uuid('admin_id');
                $table->uuid('target_user_id')->nullable();
                $table->string('action')->comment('e.g. Upgrade Plan, Block User, Manual Expiry Update');
                $table->text('details')->nullable()->comment('JSON payload or description of the change');
                $table->timestamps();

                $table->foreign('admin_id')->references('id')->on('admins')->cascadeOnDelete();
                $table->foreign('target_user_id')->references('id')->on('users')->nullOnDelete();
                $table->index('action');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_verified', 'is_blocked', 'admin_note']);
        });
    }
};
