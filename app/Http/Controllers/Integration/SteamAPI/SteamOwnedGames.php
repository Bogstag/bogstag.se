<?php

namespace App\Http\Controllers\Integration\SteamAPI;

use App\SteamGame;
use Carbon\Carbon;
use Log;
use Steam\Command\PlayerService\GetOwnedGames;

class SteamOwnedGames extends SteamAPI
{
    public function updateGamesFromAPI()
    {
        $oldestDate = new Carbon(SteamGame::min('updated_at'));
        if ($oldestDate->toDateString() >= date('Y-m-d')) {
            Log::info(date('Y-m-d H:i:s').' No more games to update');
        } else {
            $this->getSteamOwnedGamesFromAPI();
            $this->parseAndSaveSteamGame();
        }
    }

    public function getSteamOwnedGamesFromAPI()
    {
        $GetOwnedGames = new GetOwnedGames(env('STEAM_64ID', false));
        $this->OwnedGamesFromAPI = $this->steam->run($GetOwnedGames->setIncludeAppInfo(1));
    }

    private function parseAndSaveSteamGame()
    {
        foreach ($this->OwnedGamesFromAPI['response']['games'] as $game) {
            $SteamGame = SteamGame::find($game['appid']);
            if ($SteamGame === null) {
                $SteamGame = new SteamGame();
                $SteamGame->id = $game['appid'];
            }

            if (isset($game['name'])) {
                $SteamGame->name = $game['name'];
            }

            if (isset($game['playtime_forever'])) {
                $SteamGame->playtimeforever = $game['playtime_forever'];
            }

            if (isset($game['playtime_2weeks'])) {
                $SteamGame->playtime2weeks = $game['playtime_2weeks'];
            } else {
                $SteamGame->playtime2weeks = 0;
            }

            $imgUrl = 'http://media.steampowered.com/steamcommunity/public/images/apps/'.$SteamGame->id.'/';
            if (isset($game['img_icon_url'])) {
                $SteamGame->iconurl = $imgUrl.$game['img_icon_url'].'.jpg';
            }

            if (isset($game['img_logo_url'])) {
                $SteamGame->logourl = $imgUrl.$game['img_logo_url'].'.jpg';
            }

            if (isset($game['has_community_visible_stats'])) {
                $SteamGame->hasstats = $game['has_community_visible_stats'];
            } else {
                $SteamGame->hasstats = false;
            }
            $SteamGame->save();
        }
    }
}
