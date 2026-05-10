<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckSubscriptionExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:check-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired subscriptions and deactivate websites. Runs daily at midnight.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Checking for expired subscriptions...');

        // Find all Active users whose subscription has expired
        $expiredUsers = User::where('subscription_status', 'Active')
            ->whereNotNull('subscription_expires_at')
            ->where('subscription_expires_at', '<', now())
            ->get();

        if ($expiredUsers->isEmpty()) {
            $this->info('✅ No expired subscriptions found.');

            return self::SUCCESS;
        }

        $count = $expiredUsers->count();
        $this->warn("⚠️  Found {$count} expired subscription(s).");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($expiredUsers as $user) {
            DB::transaction(function () use ($user) {
                // Update user subscription status
                $user->update([
                    'subscription_status' => 'Expired',
                ]);

                // Deactivate all websites for this user
                Website::where('user_id', $user->id)->update([
                    'is_active' => false,
                ]);
            });

            Log::info("Subscription expired for user {$user->email} (ID: {$user->id}). Websites deactivated.");

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Successfully processed {$count} expired subscription(s).");

        return self::SUCCESS;
    }
}
