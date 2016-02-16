<?php

namespace App\Http\Controllers\Integration\SteamAPI;

use App\SteamAchievement;
use App\SteamGame;
use App\SteamStat;
use Steam\Command\UserStats\GetUserStatsForGame;

class SteamGameAchievements extends SteamAPI
{
    public function getSteamAchievements()
    {
        $GamesWithAchievements = SteamGame::AchievementsNeedUpdate()->get();
        if ($GamesWithAchievements->isEmpty()) {
            abort(200, date('Y-m-d H:i:s').' No more achievements to update');
        }

        foreach ($GamesWithAchievements as $GameWithAchievements) {
            $this->getSteamGameAchievementsFromAPI($GameWithAchievements->id);
            if (empty($this->Achievements['playerstats'])) {
                continue;
            } else {
                if (!empty($this->Achievements['playerstats']['achievements'])) {
                    $this->parseAndSaveAchievements($GameWithAchievements->id);
                }
                if (!empty($this->Achievements['playerstats']['stats'])) {
                    $this->parseAndSaveStats($GameWithAchievements->id);
                }
                $this->setGameTimestampForAchievement($GameWithAchievements->id);
            }
        }
    }

    public function getSteamGameAchievementsFromAPI($appid)
    {
        $GetAchievements = new GetUserStatsForGame(env('STEAM_64ID', false), $appid);
        $this->Achievements = $this->steam->run($GetAchievements);
    }

    /**
     * @param $GameId
     */
    private function parseAndSaveAchievements($GameId)
    {
        foreach ($this->Achievements['playerstats']['achievements'] as $achievement) {
            if (empty($achievement)) {
                continue;
            } else {
                $SteamAchievement = SteamAchievement::where('steam_games_id', $GameId)->where(
                    'name',
                    $achievement['name']
                )->first();

                if ($SteamAchievement === null) {
                    $SteamAchievement = new SteamAchievement();
                    $SteamAchievement->steam_games_id = $GameId;
                    $SteamAchievement->name = $achievement['name'];
                }

                if (isset($achievement['achieved'])) {
                    $SteamAchievement->value = $achievement['achieved'];
                }
                $SteamAchievement->save();
            }
        }
    }

    private function parseAndSaveStats($GameId)
    {
        foreach ($this->Achievements['playerstats']['stats'] as $stat) {
            if (empty($stat)) {
                continue;
            } else {
                $SteamStat = SteamStat::where('steam_games_id', $GameId)->where(
                    'name',
                    $stat['name']
                )->first();
                if ($SteamStat === null) {
                    $SteamStat = new SteamStat();
                    $SteamStat->steam_games_id = $GameId;
                    $SteamStat->name = $stat['name'];
                }

                if (isset($stat['value'])) {
                    $SteamStat->value = $stat['value'];
                }
                $SteamStat->save();
            }
        }
    }

    private function setGameTimestampForAchievement($GameId)
    {
        $Game = SteamGame::select('id', 'player_stats_updated_at')->where('id', $GameId)->first();
        $Game->player_stats_updated_at = date('Y-m-d H:i:s');
        $Game->save();
    }
}
