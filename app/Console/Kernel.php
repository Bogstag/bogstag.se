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

        /* Update steam game @ 0000 */
        $schedule->call(function () {
            $SteamOwnedGames = new SteamOwnedGames();
            $SteamOwnedGames->updateGamesFromAPI();
        })->everyMinute();

        /* Update steam game schema @ 0015, 0115, 0315, 0615 */
        $schedule->call(function () {
            $SteamOwnedGames = new SteamGameSchema();
            $SteamOwnedGames->updateSteamGameSchemas();
        })->everyMinute();

        /* Update steam game achive @ 0020, 0120, 0320, 0620 */
        $schedule->call(function () {
            $SteamOwnedGames = new SteamGameAchievements();
            $SteamOwnedGames->getSteamAchievements();
        })->everyMinute();

        /* Update steam game achive @ 0020, 0120, 0320, 0620 */
        $schedule->call(function () {
            $SteamOwnedGames = new SteamGameDescriptions();
            $SteamOwnedGames->updateSteamGameDescription();
        })->everyMinute();
    }
}
