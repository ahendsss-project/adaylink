<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks (Cron Jobs)
|--------------------------------------------------------------------------
|
| Register scheduled tasks here. These will run automatically via
| Laravel's task scheduler when `php artisan schedule:run` is executed.
|
| On production, add this cron entry:
| * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
|
*/

// Check for expired subscriptions daily at midnight
Schedule::command('subscription:check-expiry')
    ->daily()
    ->at('00:00')
    ->timezone('Asia/Makassar')
    ->withoutOverlapping()
    ->onOneServer();
