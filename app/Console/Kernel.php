<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel.
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [\App\Console\Commands\Inspire::class,
        \App\Console\Commands\SteamApiCommand::class,
        \App\Console\Commands\GoogleFitCommand::class,
        \App\Console\Commands\Oauth2TokenCommand::class,
        \App\Console\Commands\LoadTraktTvCommand::class,
        \App\Console\Commands\SyncTraktTvCommand::class, ];

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
        $schedule->command('googlefit:steps update')->everyFiveMinutes();

        /* Update steam game i have played */
        $schedule->command('steamapi:game update --rpg')->hourly();

        /* Update oauth2 tokens */
        $schedule->command('oauth2token:refresh')->weekly();

        /* Update trakt.tv movies */
        $schedule->command('trakttv:sync movie')->twiceDaily(19, 23);
    }
}
