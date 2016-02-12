<?php

namespace App\Console;

use App\Http\Controllers\Integration\SteamAPI\SteamOwnedGames;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\Integration\Google\GoogleFit;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->call(function () {
            $GoogleFit = new GoogleFit;
            $GoogleFit->getStepData();
        })->everyThirtyMinutes();

        $schedule->call(function () {
            $SteamOwnedGames = new SteamOwnedGames();
            $SteamOwnedGames->updateGamesFromAPI();
        })->daily();
    }
}
