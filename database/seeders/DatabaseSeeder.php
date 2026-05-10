<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ─── Create Roles ───
        $superAdminRole = Role::create([
            'role_name' => 'Super Admin',
            'description' => 'Full access to all platform features',
        ]);

        Role::create([
            'role_name' => 'Editor',
            'description' => 'Can manage blog posts and content',
        ]);

        // ─── Run Phase 2 Seeders ───
        $this->call([
            SubscriptionPlanSeeder::class,
            TemplateSeeder::class,
        ]);

        // ─── Create Dummy Admin ───
        Admin::create([
            'role_id' => $superAdminRole->id,
            'email' => 'admin@adaylink.com',
            'password' => Hash::make('password'),
            'full_name' => 'Admin adaylink',
            'is_active' => true,
        ]);

        // ─── Create Dummy Driver/Tenant ───
        $proPlan = \App\Models\SubscriptionPlan::where('name', 'Pro Agent')->first();

        User::create([
            'plan_id' => $proPlan?->id,
            'email' => 'driver@adaylink.com',
            'password' => Hash::make('password'),
            'full_name' => 'Budi Santoso',
            'phone' => '081234567890',
            'subscription_plan' => 'Pro',
            'subscription_status' => 'Active',
            'subscription_expires_at' => now()->addDays(30),
        ]);

        $this->command->info('');
        $this->command->info('✅ All seeders complete:');
        $this->command->info('   👤 Admin  → admin@adaylink.com / password');
        $this->command->info('   🚗 Driver → driver@adaylink.com / password (Pro Agent)');
    }
}
