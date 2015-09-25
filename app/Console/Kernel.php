<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\Api\DateTime\DateController;
use App\Http\Controllers\Api\Activity\StepController;
use App\Http\Controllers\Integration\Google\GoogleFit;
use App\Step;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        'App\Console\Commands\getFromFit'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();

        $schedule->call(function () {
            $GoogleFit = new GoogleFit;
            $GoogleFit->getStepData();
        })->everyThirtyMinutes();
    }
}