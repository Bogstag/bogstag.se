<?php

namespace App\Http\Controllers\Integration\SteamAPI;

use App\Http\Controllers\Integration\Integrator;
use GuzzleHttp\Client;
use Steam\Configuration;
use Steam\Runner\DecodeJsonStringRunner;
use Steam\Runner\GuzzleRunner;
use Steam\Steam;
use Steam\Utility\GuzzleUrlBuilder;

/**
 * Class SteamAPI.
 */
class SteamAPI extends Integrator
{
    /**
     * SteamAPI constructor.
     */
    public function __construct()
    {
        $this->steam = new Steam(new Configuration([
            Configuration::STEAM_KEY => env('STEAM_KEY', false),
        ]));
        $this->steam->addRunner(new GuzzleRunner(new Client(), new GuzzleUrlBuilder()));
        $this->steam->addRunner(new DecodeJsonStringRunner());
    }
}
