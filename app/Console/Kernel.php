<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\CheckTransactionDeadlines::class,
        \App\Console\Commands\CheckTransaction::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Schedule the check:transaction-deadlines command to run every minute
        Log::info('Scheduler is running.');
        $schedule->command('check:transaction-deadlines')->everyMinute();
        $schedule->command('check:transaction')->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
