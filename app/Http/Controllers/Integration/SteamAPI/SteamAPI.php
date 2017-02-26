<?php

namespace App\Http\Controllers\Integration\SteamAPI;

use Steam\Steam;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Steam\Configuration;
use Steam\Runner\GuzzleRunner;
use Steam\Utility\GuzzleUrlBuilder;
use Steam\Runner\DecodeJsonStringRunner;
use App\Http\Controllers\Integration\Integrator;

/**
 * Class SteamAPI.
 */
class SteamAPI extends Integrator
{
    /**
     * @var int
     */
    public $externalApiLimit = 100000;

    /**
     * @var string
     */
    public $externalApiLimitInterval = 'Day';

    /**
     * @var string
     */
    public $externalApiName = 'SteamApi';

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

    public function incrementSteamApiLimitCounter()
    {
        $this->addExternalAPILimitCounter(
            Carbon::now(),
            $this->externalApiName,
            $this->externalApiLimit,
            $this->externalApiLimitInterval
        );
    }
}
