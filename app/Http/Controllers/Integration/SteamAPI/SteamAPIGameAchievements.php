<?php

namespace App\Http\Controllers\Integration\SteamAPI;

use Log;
use App\SteamGame;
use App\SteamGameStat;
use App\SteamGameAchievement;
use Steam\Command\UserStats\GetUserStatsForGame;

/**
 * Class SteamAPIGameAchievements.
 */
class SteamAPIGameAchievements extends SteamAPIGame
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
     * SteamAPIGameAchievements constructor.
     *
     * @param SteamGame $SteamGame
     */
    public function __construct(SteamGame $SteamGame)
    {
        parent::__construct();
        if (! $SteamGame->id || in_array($SteamGame->id, SteamGame::getGamesWithNoStats())) {
            return false;
        }
        $this->localfile = 'SteamApi/GetUserStatsForGame/'.$SteamGame->id.'.json';

        $SteamGameAchievementsFromAPI = $this->getUserStatsForGame($SteamGame->id);

        if (empty($SteamGameAchievementsFromAPI)) {
            return false;
        }
        if (! empty($SteamGameAchievementsFromAPI->achievements)) {
            $this->parseAndSaveAchievements($SteamGame->id, $SteamGameAchievementsFromAPI->achievements);
        }
        if (! empty($SteamGameAchievementsFromAPI->stats)) {
            $this->parseAndSaveStats($SteamGame->id, $SteamGameAchievementsFromAPI->stats);
        }
        if (! empty($SteamGameAchievementsFromAPI->achievements) || ! empty($SteamGameAchievementsFromAPI->stats)) {
            $this->success = $this->setGameTimestampForAchievement($SteamGame->id);
        }
        if ($this->success === true) {
            Log::info('Achievements was updated for '.$SteamGame->name.' : '.$SteamGame->id);
        } else {
            Log::error('Achievements was NOT updated for '.$SteamGame->name.' : '.$SteamGame->id);
        }

        return true;
    }

    /**
     * @param $GameId
     *
     * @return bool|mixed
     */
    public function getUserStatsForGame($GameId)
    {
        if (env('APP_ENV', false) == 'local' && $cachedAPICall = $this->getCachedAPICall($this->localfile)) {
            return $cachedAPICall;
        }
        $GetAchievements = new GetUserStatsForGame(env('STEAM_64ID', false), $GameId);
        $GetAchievements = $this->steam->run($GetAchievements);

        if (empty($GetAchievements['playerstats'])) {
            return false;
        }

        $GetAchievementsJson = json_encode($GetAchievements['playerstats']);

        $this->incrementSteamApiLimitCounter();

        if ((env('APP_ENV', false) == 'local')) {
            $this->saveCachedAPICall($this->localfile, $GetAchievementsJson);
        }

        return json_decode($GetAchievementsJson);
    }

    /**
     * @param $GameId
     * @param $SteamGameAchievementsFromAPI
     *
     * @return bool
     */
    private function parseAndSaveAchievements($GameId, $SteamGameAchievementsFromAPI)
    {
        foreach ($SteamGameAchievementsFromAPI as $achievement) {
            if (empty($achievement)) {
                continue;
            }
            $SteamAchievement = SteamGameAchievement::firstOrNew(
                ['steam_game_id' => $GameId, 'name' => $achievement->name]
            );

            if (! empty($achievement->achieved)) {
                $SteamAchievement->value = $achievement->achieved;
            }

            $SteamAchievement->save();
        }

        return true;
    }

    /**
     * @param $GameId
     * @param $SteamGameAchievementsFromAPI
     *
     * @return bool
     */
    private function parseAndSaveStats($GameId, $SteamGameAchievementsFromAPI)
    {
        foreach ($SteamGameAchievementsFromAPI as $stat) {
            if (empty($stat)) {
                continue;
            }
            $SteamStat = SteamGameStat::firstOrNew(
                ['steam_game_id' => $GameId, 'name' => $stat->name]
            );

            if (! empty($stat->value)) {
                $SteamStat->value = $stat->value;
            }
            $SteamStat->save();
        }

        return true;
    }

    /**
     * @param $GameId
     *
     * @return mixed
     */
    private function setGameTimestampForAchievement($GameId)
    {
        $SteamGame = SteamGame::find($GameId);
        $SteamGame->player_stats_updated_at = date('Y-m-d H:i:s');

        return $SteamGame->save();
    }
}
