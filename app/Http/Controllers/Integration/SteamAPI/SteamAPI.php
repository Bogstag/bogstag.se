<?php

namespace App\Http\Controllers\Integration\SteamAPI;

use App\Http\Controllers\Integration\Integrator;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Steam\Command\PlayerService\GetOwnedGames;
use Steam\Command\PlayerService\GetRecentlyPlayedGames;
use Steam\Command\UserStats\GetSchemaForGame;
use Steam\Command\UserStats\GetUserStatsForGame;
use Steam\Configuration;
use Steam\Runner\DecodeJsonStringRunner;
use Steam\Runner\GuzzleRunner;
use Steam\Steam;
use Steam\Utility\GuzzleUrlBuilder;
use Storage;

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

    public function getOwnedGames()
    {
        // todo refactor
        $localfile = 'SteamApi/GetOwnedGames.json';
        if (Storage::disk('local')->exists($localfile) && (env('APP_ENV', false) == 'local')) {
            return json_decode(Storage::get($localfile));
        };
        $GetOwnedGames = new GetOwnedGames(env('STEAM_64ID', false));
        $GetOwnedGames = $this->steam->run($GetOwnedGames->setIncludeAppInfo(1));
        $GetOwnedGames = json_encode($GetOwnedGames['response']['games']);
        $this->incrementSteamApiLimitCounter();
        if ((env('APP_ENV', false) == 'local')) {
            Storage::put($localfile, $GetOwnedGames);
        }

        return json_decode($GetOwnedGames);
    }

    public function getSteamGameDescriptionFromUrl($GameId)
    {
        // todo refactor
        $localfile = 'SteamApi/SteamApiAppDetails/'.$GameId.'.json';
        if (Storage::disk('local')->exists($localfile) && (env('APP_ENV', false) == 'local')) {
            return json_decode(Storage::get($localfile));
        };

        $url = 'http://store.steampowered.com/api/appdetails?appids='.$GameId;
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, $url);
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

        $GameDescriptionJson = json_decode(curl_exec($curlSession));

        if (empty($GameDescriptionJson->$GameId->data)) {
            return;
        }
        $GameDescriptionJson = json_encode($GameDescriptionJson->$GameId->data);
        curl_close($curlSession);
        $this->addExternalAPILimitCounter(Carbon::now(), 'SteamApiAppDetails', 200, '5min');
        if ((env('APP_ENV', false) == 'local')) {
            Storage::put($localfile, $GameDescriptionJson);
        }

        return json_decode($GameDescriptionJson);
    }

    public function getSchemaForGame($GameId)
    {
        // todo refactor
        $localfile = 'SteamApi/GetSchemaForGame/'.$GameId.'.json';
        if (Storage::disk('local')->exists($localfile) && (env('APP_ENV', false) == 'local')) {
            return json_decode(Storage::get($localfile));
        };
        $GetSchema = new GetSchemaForGame($GameId);
        $GetSchema = $this->steam->run($GetSchema);
        if (empty($GetSchema['game']['availableGameStats'])) {
            return;
        }
        $GetSchema = json_encode($GetSchema['game']['availableGameStats']);
        if ((env('APP_ENV', false) == 'local')) {
            Storage::put($localfile, $GetSchema);
        }
        $this->incrementSteamApiLimitCounter();

        return json_decode($GetSchema);
    }

    public function getUserStatsForGame($GameId)
    {
        // todo refactor
        $localfile = 'SteamApi/GetUserStatsForGame/'.$GameId.'.json';
        if (Storage::disk('local')->exists($localfile) && (env('APP_ENV', false) == 'local')) {
            return json_decode(Storage::get($localfile));
        };
        $GetAchievements = new GetUserStatsForGame(env('STEAM_64ID', false), $GameId);
        $GetAchievements = $this->steam->run($GetAchievements);
        if (empty($GetAchievements['playerstats'])) {
            return;
        }
        $GetAchievements = json_encode($GetAchievements['playerstats']);
        if ((env('APP_ENV', false) == 'local')) {
            Storage::put($localfile, $GetAchievements);
        }
        $this->incrementSteamApiLimitCounter();

        return json_decode($GetAchievements);
    }

    public function getRecentlyPlayedGames()
    {
        // todo refactor
        $localfile = 'SteamApi/GetRecentlyPlayedGames.json';
        if (Storage::disk('local')->exists($localfile) && (env('APP_ENV', false) == 'local')) {
            return json_decode(Storage::get($localfile));
        };
        $GetRecentlyPlayedGames = new GetRecentlyPlayedGames(env('STEAM_64ID', false));
        $GetRecentlyPlayedGames = $this->steam->run($GetRecentlyPlayedGames);
        if (empty($GetRecentlyPlayedGames['response']['games'])) {
            return;
        }
        $GetRecentlyPlayedGames = json_encode($GetRecentlyPlayedGames['response']['games']);
        if ((env('APP_ENV', false) == 'local')) {
            Storage::put($localfile, $GetRecentlyPlayedGames);
        }
        $this->incrementSteamApiLimitCounter();

        return json_decode($GetRecentlyPlayedGames);
    }

    private function incrementSteamApiLimitCounter()
    {
        $this->addExternalAPILimitCounter(
            $now = Carbon::now(),
            $externalApiName = $this->externalApiName,
            $externalApiLimit = $this->externalApiLimit,
            $externalApiLimitInterval = $this->externalApiLimitInterval
        );
    }
}
