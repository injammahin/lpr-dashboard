<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ScanLprFiles;
use App\Jobs\ScanLprFilesJob;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
     protected $commands = [
        ScanLprFiles::class, // Add this line to register the command
    ];

protected function schedule(Schedule $schedule): void
{
    $schedule->job(new ScanLprFilesJob)->everyMinute();
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
