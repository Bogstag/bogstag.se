<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Integration\SteamAPI\SteamAPIGame;

/**
 * Class SteamApiCommand.
 */
class SteamApiCommand extends Command
{
    /**
     * @var SteamAPIGame
     */
    private $steamApi;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'steamapi:game
    {action : You whant to load or update}
    {--gameid= : specify a game id to update only this game.}
    {--rpg : load only games play within 2 weeks}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for update games from steam api.
    --gameid only works with load.
    --rpg only works with update.';

    /**
     * SteamApiCommand constructor.
     *
     * @param SteamAPIGame $steamAPI
     */
    public function __construct(SteamAPIGame $steamAPI)
    {
        parent::__construct();
        $this->steamApi = $steamAPI;
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
                    $this->steamApi->loadGame($this->option('gameid'));
                    $this->info('Done loading game with id: '.$this->option('gameid').' to db');
                    break;
                }
                $this->info('Load all games to db');
                $this->steamApi->loadGames();
                $this->info('Done loading all games to db');
                break;
            case 'update':
                if ($this->option('rpg')) {
                    $this->info('Updating games');
                    $this->steamApi->updateRecentlyPlayedGames();
                    $this->info('Done updating games');
                    break;
                }
                $this->info('Updating games');
                $this->steamApi->updateGames();
                $this->info('Done updating games');
                break;
            default:
                echo 'Use load or update';
        }
    }
}
