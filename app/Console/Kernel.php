<?php

namespace App\Console;

use App\Http\Controllers\Integration\Google\GoogleFit;
use App\Http\Controllers\Integration\SteamAPI\SteamGameAchievements;
use App\Http\Controllers\Integration\SteamAPI\SteamGameSchema;
use App\Http\Controllers\Integration\SteamAPI\SteamOwnedGames;
use App\SteamGameDescription;
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

        /* Update steam game @ 0000 */
        $schedule->call(function () {
            $SteamOwnedGames = new SteamOwnedGames();
            $SteamOwnedGames->updateGamesFromAPI();
        })->daily();

        /* Update steam game schema @ 0015, 0115, 0315, 0615 */
        $schedule->call(function () {
            $SteamOwnedGames = new SteamGameSchema();
            $SteamOwnedGames->updateSteamGameSchemas();
        })->cron('15 0,1,3,6 * * * *');

        /* Update steam game achive @ 0020, 0120, 0320, 0620 */
        $schedule->call(function () {
            $SteamOwnedGames = new SteamGameAchievements();
            $SteamOwnedGames->getSteamAchievements();
        })->cron('20 0,1,3,6 * * * *');

        /* Update steam game achive @ 0020, 0120, 0320, 0620 */
        $schedule->call(function () {
            $SteamOwnedGames = new SteamGameDescription();
            $SteamOwnedGames->updateSteamGameDescription();
        })->cron('25 0,1,3,6 * * * *');
    }
}
