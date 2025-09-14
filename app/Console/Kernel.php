<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        // Register your commands here
        \App\Console\Commands\TestPayment::class,
        \App\Console\Commands\DeleteInactiveUsers::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Schedule the deletion of expired inactive users daily at midnight
        $schedule->job(new \App\Jobs\DeleteExpiredInactiveUsers)->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
