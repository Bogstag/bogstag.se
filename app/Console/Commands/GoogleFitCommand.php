<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class SteamApiCommand.
 */
class GoogleFitCommand extends Command
{
    /**
     * @var GoogleFit|\App\Http\Controllers\Integration\Google\GoogleFit
     */
    private $googleFit;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'googlefit:steps
    {action : You whant to load or update}
    {--days= : How many days backwards should you fetch data.}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for update steps and duration from Google Fit API';

    /**
     * SteamApiCommand constructor.
     *
     * @param GoogleFit $googleFit
     */
    public function __construct(\App\Http\Controllers\Integration\Google\GoogleFit $googleFit)
    {
        parent::__construct();
        $this->googleFit = $googleFit;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $action = $this->argument('action');
        switch ($action) {
            case 'update':
                if ($this->option('days')) {
                    $this->info('Updating steps');
                    $this->googleFit->getStepData($this->option('days'));
                    $this->info('Done updating steps');
                    break;
                }
                $this->info('Updating steps');
                $this->googleFit->getStepData();
                $this->info('Done updating steps');
                break;
            default:
                echo 'Use update';
        }
    }
}
