<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

     /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @deprecated use $this->plan()
     */
    protected function schedule(Schedule $schedule) : void
    {
        throw new \LogicException('Unsafe cron implementing');
    }
}
