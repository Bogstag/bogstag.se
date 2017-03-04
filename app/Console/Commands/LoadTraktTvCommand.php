<?php

namespace App\Console\Commands;

use App\Http\Controllers\Integration\TraktTv\TraktTv;
use Illuminate\Console\Command;

/**
 * Class SteamApiCommand.
 */
class LoadTraktTvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trakttv:load {action : movie}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for loading from trakt to db';

    /**
     * @var TraktTv
     */
    private $traktTv;

    /**
     * LoadTraktTvCommand constructor.
     *
     * @param TraktTv $traktTv
     */
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
                $this->info('Load all movies to db');
                $this->traktTv->setType('movies');
                $this->traktTv->setExtended('full');
                $this->traktTv->loadWatched($this);
                $this->info('Done loading all movies to db');
                break;
            default:
                echo 'Use movie';
        }
    }
}
