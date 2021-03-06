<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\CreateAdminUser',
        'App\Console\Commands\CreateEolMessages',
        'App\Console\Commands\GatherBinaryVersions',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('binarymngr:create-eol-messages')->daily();
        $schedule->command('binarymngr:gather-binary-versions')->daily();
    }
}
