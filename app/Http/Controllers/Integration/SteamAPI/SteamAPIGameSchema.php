<?php

namespace App\Http\Controllers\Integration\SteamAPI;

use App\SteamGame;
use App\SteamGameAchievement;
use App\SteamGameStat;
use Log;
use Steam\Command\UserStats\GetSchemaForGame;

/**
 * Class SteamAPIGameSchema.
 */
class SteamAPIGameSchema extends SteamAPIGame
{
    /**
     * @var string
     */
    private $localfile;

    /**
     * @var bool
     */
    private $success = false;

    /**
     * SteamAPIGameSchema constructor.
     *
     * @param SteamGame $SteamGame
     */
    public function __construct(SteamGame $SteamGame)
    {
        parent::__construct();
        if (!$SteamGame->id || in_array($SteamGame->id, SteamGame::getGamesWithNoStats())) {
            return false;
        }
        $this->localfile = 'SteamApi/GetSchemaForGame/'.$SteamGame->id.'.json';

        $SteamGameSchemaFromAPI = $this->getSchemaForGameFromApi($SteamGame->id);

        if (empty($SteamGameSchemaFromAPI)) {
            return false;
        }
        if (!empty($SteamGameSchemaFromAPI->achievements)) {
            $this->parseAndSaveAchievementSchema($SteamGame->id, $SteamGameSchemaFromAPI->achievements);
        }
        if (!empty($SteamGameSchemaFromAPI->stats)) {
            $this->parseAndSaveStatSchema($SteamGame->id, $SteamGameSchemaFromAPI->stats);
        }
        if (!empty($SteamGameSchemaFromAPI->achievements) || !empty($SteamGameSchemaFromAPI->stats)) {
            $this->success = $this->setGameTimestampForSchema($SteamGame->id);
        }
        if ($this->success === true) {
            Log::info('Schema was updated for '.$SteamGame->name.' : '.$SteamGame->id);
        } else {
            Log::error('Schema was NOT updated for '.$SteamGame->name.' : '.$SteamGame->id);
        }

        return true;
    }

    /**
     * @param $GameId
     *
     * @return bool|mixed
     */
    public function getSchemaForGameFromApi($GameId)
    {
        if (env('APP_ENV', false) == 'local' && $cachedAPICall = $this->getCachedAPICall($this->localfile)) {
            return $cachedAPICall;
        }

        $GetSchema = new GetSchemaForGame($GameId);
        $GetSchema = $this->steam->run($GetSchema);
        if (empty($GetSchema['game']['availableGameStats'])) {
            return false;
        }

        $GameSchemaJson = json_encode($GetSchema['game']['availableGameStats']);

        $this->incrementSteamApiLimitCounter();

        if ((env('APP_ENV', false) == 'local')) {
            $this->saveCachedAPICall($this->localfile, $GameSchemaJson);
        }

        return json_decode($GameSchemaJson);
    }

    /**
     * @param $GameId
     * @param $SteamGameSchemaFromAPI
     *
     * @return bool
     */
    private function parseAndSaveAchievementSchema($GameId, $SteamGameSchemaFromAPI)
    {
        if (!$GameId) {
            return false;
        }
        foreach ($SteamGameSchemaFromAPI as $achievement) {
            if (empty($achievement)) {
                continue;
            }
            $SteamAchievement = SteamGameAchievement::firstOrNew(
                ['steam_game_id' => $GameId, 'name' => $achievement->name]
            );

            if (!empty($achievement->displayName)) {
                $SteamAchievement->display_name = $achievement->displayName;
            }

            if (!empty($achievement->hidden)) {
                $SteamAchievement->hidden = $achievement->hidden;
            }

            if (!empty($achievement->description)) {
                $SteamAchievement->description = $achievement->description;
            }

            if (!empty($achievement->icon)) {
                $SteamAchievement->icon_url = $achievement->icon;
            }

            if (!empty($achievement->icongray)) {
                $SteamAchievement->icon_gray_url = $achievement->icongray;
            }
            $SteamAchievement->save();
        }

        return true;
    }

    /**
     * @param $GameId
     * @param $SteamGameSchemaFromAPI
     *
     * @return bool
     */
    private function parseAndSaveStatSchema($GameId, $SteamGameSchemaFromAPI)
    {
        if (!$GameId) {
            return false;
        }
        foreach ($SteamGameSchemaFromAPI as $stat) {
            if (empty($stat)) {
                continue;
            }
            $SteamGameStat = SteamGameStat::firstOrNew(
                ['steam_game_id' => $GameId, 'name' => $stat->name]
            );

            if (!empty($stat->displayName)) {
                $SteamGameStat->display_name = $stat->displayName;
            } else {
                $SteamGameStat->display_name = null;
            }
            $SteamGameStat->save();
        }

        return true;
    }

    /**
     * @param $GameId
     *
     * @return mixed
     */
    private function setGameTimestampForSchema($GameId)
    {
        $SteamGame = SteamGame::find($GameId);
        $SteamGame->schema_updated_at = date('Y-m-d H:i:s');

        return $SteamGame->save();
    }
}
