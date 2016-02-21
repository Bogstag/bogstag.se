<?php

namespace App\Console;

use App\Http\Controllers\Integration\Google\GoogleFit;
use App\Http\Controllers\Integration\SteamAPI\SteamGameAchievements;
use App\Http\Controllers\Integration\SteamAPI\SteamGameDescriptions;
use App\Http\Controllers\Integration\SteamAPI\SteamGameSchema;
use App\Http\Controllers\Integration\SteamAPI\SteamOwnedGames;
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
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\SteamApiCommand::class,
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
        $schedule->call(function () {
            $GoogleFit = new GoogleFit();
            $GoogleFit->getStepData();
        })->everyThirtyMinutes();

        /* Update steam game i have played */
        $schedule->command('steamapi:game update --rpg')->twiceDaily(10, 22);
    }
}
