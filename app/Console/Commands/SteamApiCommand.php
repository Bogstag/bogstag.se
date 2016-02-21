<?php

namespace App\Console\Commands;

use App\Http\Controllers\Integration\SteamAPI\SteamAPIGame;
use Illuminate\Console\Command;

class SteamApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'steamapi:game
    {action : You whant to load or update}
    {--gameid= : specify a game id to update only this game.}
    {--rpg : load pnly games play within 2 weeks}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for update games from steam api.
    gameid only works with load.
    rpg only works with update.';

    /**
     * Create a new command instance.
     *
     */
    public function __construct(SteamAPIGame $steamAPI)
    {
        parent::__construct();

        $this->steamapi = $steamAPI;
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
            case 'load':
                if ($this->option('gameid')) {
                    $this->info('Load game with id: '.$this->option('gameid').' to db');
                    $this->steamapi->loadGame($this->option('gameid'));
                    $this->info('Done loading game with id: '.$this->option('gameid').' to db');
                    break;
                }
                $this->info('Load all games to db');
                $this->steamapi->loadGames();
                $this->info('Done loading all games to db');
                break;
            case 'update':
                if ($this->option('rpg')) {
                    $this->info('Updating games');
                    $this->steamapi->updateRecentlyPlayedGames();
                    $this->info('Done updating games');
                    break;
                }
                $this->info('Updating games');
                $this->steamapi->updateGames();
                $this->info('Done updating games');
                break;
            default:
                echo 'Use load or update';
        }
    }
}
