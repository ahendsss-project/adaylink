<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Change subscription_plan from enum to varchar so it can accept
     * any plan name from the subscription_plans table (e.g. 'Starter', 'Pro Agent').
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // SQLite doesn't support ALTER COLUMN, recreate the table
            DB::statement('CREATE TABLE users_temp AS SELECT * FROM users');
            DB::statement('DROP TABLE users');
            DB::statement('
                CREATE TABLE users (
                    id VARCHAR(36) PRIMARY KEY,
                    email VARCHAR(255) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    full_name VARCHAR(255) NOT NULL,
                    phone VARCHAR(255),
                    subscription_plan VARCHAR(255) NOT NULL DEFAULT \'Free\',
                    subscription_status VARCHAR(255) NOT NULL DEFAULT \'Pending\',
                    subscription_expires_at TIMESTAMP,
                    remember_token VARCHAR(100),
                    plan_id BIGINT UNSIGNED,
                    created_at TIMESTAMP,
                    updated_at TIMESTAMP
                )
            ');
            DB::statement('INSERT INTO users SELECT * FROM users_temp');
            DB::statement('DROP TABLE users_temp');
        } else {
            // MySQL / MariaDB
            DB::statement("ALTER TABLE users MODIFY COLUMN subscription_plan VARCHAR(255) NOT NULL DEFAULT 'Free'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN subscription_plan ENUM('Free', 'Pro', 'Premium') NOT NULL DEFAULT 'Free'");
        }
    }
};
