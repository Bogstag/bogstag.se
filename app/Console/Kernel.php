<?php

namespace App\Console;

use App\Console\Commands\GoogleFitCommand;
use App\Console\Commands\Inspire;
use App\Console\Commands\SteamApiCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel
 * @package App\Console
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Inspire::class,
        SteamApiCommand::class,
        GoogleFitCommand::class,
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

        /* Update steps i have walked */
        $schedule->command('googlefit:steps update')->everyTenMinutes();

        /* Update steam game i have played */
        $schedule->command('steamapi:game update --rpg')->twiceDaily(10, 22);
    }
}
