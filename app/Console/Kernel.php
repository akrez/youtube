<?php

namespace App\Console;

use App\Jobs\GetTelegramUpdatesJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $now = now();

        $schedule
            ->job(GetTelegramUpdatesJob::class)
            ->everyFiveSeconds()
            ->withoutOverlapping()
            ->when(function () use ($now) {
                return now()->diffInSeconds($now) < 60;
            });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
