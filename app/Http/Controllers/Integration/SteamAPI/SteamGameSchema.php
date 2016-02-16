<?php

namespace App\Http\Controllers\Integration\SteamAPI;

use App\SteamAchievement;
use App\SteamGame;
use App\SteamStat;
use Steam\Command\UserStats\GetSchemaForGame;

class SteamGameSchema extends SteamAPI
{
    public function updateSteamGameSchemas()
    {
        $GameIds = SteamGame::SchemaNeedUpdate()->get();
        if ($GameIds->isEmpty()) {
            abort(200, date('Y-m-d H:i:s').' No more schemas to update');
        }
        foreach ($GameIds as $GameId) {
            $this->getSteamGameSchemaFromAPI($GameId->id);
            if (empty($this->GameSchema['game']['availableGameStats'])) {
                continue;
            } else {
                $this->parseAndSaveAchievement($GameId);
                $this->parseAndSaveStat($GameId);
                $this->setGameTimestampForSchema($GameId);
            }
        }
    }

    public function getSteamGameSchemaFromAPI($appid)
    {
        $GetSchema = new GetSchemaForGame($appid);
        $this->GameSchema = $this->steam->run($GetSchema);
    }

    /**
     * @param $GameId
     */
    private function parseAndSaveAchievement($GameId)
    {
        if (!empty($this->GameSchema['game']['availableGameStats']['achievements'])) {
            foreach ($this->GameSchema['game']['availableGameStats']['achievements'] as $achievement) {
                $SteamAchievement = SteamAchievement::where('steam_games_id', $GameId->id)->where(
                    'name',
                    $achievement['name']
                )->first();
                if ($SteamAchievement === null) {
                    $SteamAchievement = new SteamAchievement();
                    $SteamAchievement->steam_games_id = $GameId->id;
                    $SteamAchievement->name = $achievement['name'];
                }

                if (isset($achievement['displayName'])) {
                    $SteamAchievement->displayName = $achievement['displayName'];
                }

                if (isset($achievement['hidden'])) {
                    $SteamAchievement->hidden = $achievement['hidden'];
                }

                if (isset($achievement['description'])) {
                    $SteamAchievement->description = $achievement['description'];
                }

                if (isset($achievement['icon'])) {
                    $SteamAchievement->icon = $achievement['icon'];
                }

                if (isset($achievement['icongray'])) {
                    $SteamAchievement->icongray = $achievement['icongray'];
                }
                $SteamAchievement->save();
            }
        }
    }

    /**
     * @param $GameId
     */
    private function parseAndSaveStat($GameId)
    {
        if (!empty($this->GameSchema['game']['availableGameStats']['stats'])) {
            foreach ($this->GameSchema['game']['availableGameStats']['stats'] as $stats) {
                $SteamStat = SteamStat::where('steam_games_id', $GameId->id)->where(
                    'name',
                    $stats['name']
                )->first();
                if ($SteamStat === null) {
                    $SteamStat = new SteamStat();
                    $SteamStat->steam_games_id = $GameId->id;
                    $SteamStat->name = $stats['name'];
                }

                if (isset($stats['displayName']) && strlen($stats['displayName']) > 3) {
                    $SteamStat->displayName = $stats['displayName'];
                } else {
                    $SteamStat->displayName = null;
                }
                $SteamStat->save();
            }
        }
    }

    /**
     * @param $GameId
     *
     * @return mixed
     */
    private function setGameTimestampForSchema($GameId)
    {
        $Game = SteamGame::select('id', 'schema_updated_at')->where('id', $GameId->id)->first();
        $Game->schema_updated_at = date('Y-m-d H:i:s');
        $Game->save();
    }
}
