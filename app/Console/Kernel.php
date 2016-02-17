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

        /* Update steam game @ 0005, 0605,1205,1805 */
        $schedule->call(function () {
            $SteamOwnedGames = new SteamOwnedGames();
            $SteamOwnedGames->updateGamesFromAPI();
        })->cron('5 0,6,12,18 * * * *');

        /* Update steam game schema @ 0010, 0610,1210,1810  */
        $schedule->call(function () {
            $SteamOwnedGames = new SteamGameSchema();
            $SteamOwnedGames->updateSteamGameSchemas();
        })->cron('10 0,6,12,18 * * * *');

        /* Update steam game achive @ 0015, 0615,1215,1815  */
        $schedule->call(function () {
            $SteamOwnedGames = new SteamGameAchievements();
            $SteamOwnedGames->getSteamAchievements();
        })->cron('15 0,6,12,18 * * * *');

        /* Update steam game achive @ 0020, 0620,1220,1820  */
        $schedule->call(function () {
            $SteamOwnedGames = new SteamGameDescriptions();
            $SteamOwnedGames->updateSteamGameDescription();
        })->cron('20 0,6,12,18 * * * *');
    }
}
