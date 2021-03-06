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
        if (! $SteamGame->id || in_array($SteamGame->id, SteamGame::getGamesWithNoStats())) {
            return false;
        }
        $this->localfile = 'SteamApi/GetSchemaForGame/'.$SteamGame->id.'.json';

        $SteamGameSchemaFromAPI = $this->getSchemaForGameFromApi($SteamGame->id);

        if (empty($SteamGameSchemaFromAPI)) {
            return false;
        }
        if (! empty($SteamGameSchemaFromAPI->achievements)) {
            $this->parseAndSaveAchievementSchema($SteamGame->id, $SteamGameSchemaFromAPI->achievements);
        }
        if (! empty($SteamGameSchemaFromAPI->stats)) {
            $this->parseAndSaveStatSchema($SteamGame->id, $SteamGameSchemaFromAPI->stats);
        }
        if (! empty($SteamGameSchemaFromAPI->achievements) || ! empty($SteamGameSchemaFromAPI->stats)) {
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
     * @param int $GameId
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
     * @param int $GameId
     * @param     $SteamGameSchemaFromAPI
     *
     * @return bool
     */
    public function parseAndSaveAchievementSchema($GameId, $SteamGameSchemaFromAPI)
    {
        if (! $GameId) {
            return false;
        }
        foreach ($SteamGameSchemaFromAPI as $achievement) {
            if (empty($achievement)) {
                continue;
            }

            $SteamAchievement = SteamGameAchievement::firstOrNew([
                'steam_game_id' => $GameId,
                'name'          => $achievement->name,
            ]);

            $jsonModelMapping = [
                'displayName' => 'display_name',
                'hidden'      => 'hidden',
                'description' => 'description',
                'icon'        => 'icon_url',
                'icongray'    => 'icon_gray_url',
            ];
            foreach ($jsonModelMapping as $key => $val) {
                $value = $this->getValueByKey($achievement, $key);
                if (isset($value)) {
                    $SteamAchievement->{$val} = $value;
                }
            }

            $SteamAchievement->save();
        }

        return true;
    }

    /**
     * @param int $GameId
     * @param     $SteamGameSchemaFromAPI
     *
     * @return bool
     */
    public function parseAndSaveStatSchema($GameId, $SteamGameSchemaFromAPI)
    {
        if (! $GameId) {
            return false;
        }
        foreach ($SteamGameSchemaFromAPI as $stat) {
            if (empty($stat)) {
                continue;
            }
            $SteamGameStat = SteamGameStat::firstOrNew(['steam_game_id' => $GameId, 'name' => $stat->name]);

            if (! empty($stat->displayName)) {
                $SteamGameStat->display_name = $stat->displayName;
            } else {
                $SteamGameStat->display_name = null;
            }
            $SteamGameStat->save();
        }

        return true;
    }

    /**
     * @param int $GameId
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
