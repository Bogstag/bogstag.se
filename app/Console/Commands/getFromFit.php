<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\DateTime\DateController;
use Illuminate\Console\Command;
use App\Http\Controllers\Integration\Google\GoogleFit;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Http\Controllers\Api\Activity\StepController;
use App\Step;

class getFromFit extends Command implements SelfHandling
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getfromfit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data from Google fit';
    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $dateController = new DateController();
        $step = new Step();
        $stepController = new StepController($step);
        $GoogleFit = new GoogleFit;
        $GoogleFit->getStepData($dateController, $stepController);
    }
}
