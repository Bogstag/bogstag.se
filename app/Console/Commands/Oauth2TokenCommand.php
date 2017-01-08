<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\oauth2client\Oauth2ClientTrakt;

/**
 * Class Oauth2TokenCommand.
 */
class Oauth2TokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth2token:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshes oauth2 tokens';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Refreshing Tokens');
        $this->refreshTrakt();
        $this->info('Tokens refreshed');
    }

    private function refreshTrakt()
    {
        $trakt = new Oauth2ClientTrakt();
        $trakt->refreshToken();
    }
}
