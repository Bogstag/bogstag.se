<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Integration\TraktTv\TraktTv;
use App\Http\Controllers\Integration\SteamAPI\SteamAPIGame;

/**
 * Class SteamApiCommand.
 */
class SyncTraktTvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trakttv:sync {action : movie}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for syncing from trakt to db';
    /**
     * @var SteamAPIGame
     */
    private $traktTv;

    public function __construct(TraktTv $traktTv)
    {
        parent::__construct();
        $this->traktTv = $traktTv;
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
            case 'movie':
                $this->info('Sync 10 latest movies to db');
                $this->traktTv->setType('movies');
                $this->traktTv->setExtended('full,images');
                $this->traktTv->syncWatched($this);
                $this->info('Done syncing 10 latest movies to db');
                break;
            default:
                echo 'Use movie';
        }
    }
}
