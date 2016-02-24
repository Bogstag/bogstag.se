<?php

namespace App\Http\Controllers\Integration\SteamAPI;

use App\SteamGame;
use Log;
use Steam\Command\PlayerService\GetOwnedGames;
use Steam\Command\PlayerService\GetRecentlyPlayedGames;

/**
 * Class SteamAPIGame.
 */
class SteamAPIGame extends SteamAPI
{
    /**
     * @var string
     */
    private $img_base_url = 'http://media.steampowered.com/steamcommunity/public/images/apps/';

    /**
     * This should only be run from command.
     * Useful when updating empty DB.
     */
    public function loadGames()
    {
        $SteamOwnedGamesFromAPI = $this->getOwnedGames();
        foreach ($SteamOwnedGamesFromAPI as $game) {
            if (empty($game)) {
                continue;
            }
            $SteamGame = SteamGame::firstOrNew(['id' => $game->appid]);
            $this->parseAndSaveSteamGame($SteamGame, $game);
            $this->runDescriptionSchemaAchievements($SteamGame);
        }
    }

    /**
     * Can be used for testing and loading a single game by id.
     *
     * @param null $gameId
     *
     * @return string
     */
    public function loadGame($gameId = null)
    {
        if ($gameId === null) {
            return 'No game id specified';
        }
        $SteamOwnedGamesFromAPI = $this->getOwnedGames();
        foreach ($SteamOwnedGamesFromAPI as $game) {
            if ($gameId == $game->appid) {
                break;
            }
        }
        $SteamGame = SteamGame::firstOrNew(['id' => $game->appid]);
        $this->parseAndSaveSteamGame($SteamGame, $game);
        $this->runDescriptionSchemaAchievements($SteamGame);
    }

    /**
     * Not really needed to run this.
     * You can use it as a slow update of your db if you like
     * updated screenshots and movies and achievements.
     */
    public function updateGames()
    {
        $this->parseAndSaveOnlyOwnedGames();
        new SteamAPIGameDescription(SteamGame::DescriptionNeedUpdate()->first());
        new SteamAPIGameSchema(SteamGame::SchemaNeedUpdate()->first());
        new SteamAPIGameAchievements(SteamGame::AchievementsNeedUpdate()->first());
    }

    /**
     * Can be scheduled on a tighter interval.
     * Update only games that you have played last 2 weeks.
     * This also runs and updates all games (not decription, schema or achievements)
     * Needed because RecentlyPlayedGames is missing has_community_visible_stats.
     */
    public function updateRecentlyPlayedGames()
    {
        $this->parseAndSaveOnlyOwnedGames();
        $SteamOwnedGamesFromAPI = $this->getRecentlyPlayedGames();

        foreach ($SteamOwnedGamesFromAPI as $game) {
            if (empty($game)) {
                continue;
            }
            $SteamGame = SteamGame::firstOrNew(['id' => $game->appid]);
            $this->parseAndSaveSteamGame($SteamGame, $game);
            $this->runDescriptionSchemaAchievements($SteamGame);
        }
    }

    /**
     * @param SteamGame $SteamGame
     * @param $game
     *
     * @return bool
     */
    private function parseAndSaveSteamGame(SteamGame $SteamGame, $game)
    {
        if (!empty($game->name)) {
            $SteamGame->name = $game->name;
        }

        if (!empty($game->playtime_forever)) {
            $SteamGame->playtime_forever = $game->playtime_forever;
        }

        if (!empty($game->playtime_2weeks)) {
            $SteamGame->playtime_2weeks = $game->playtime_2weeks;
        } else {
            $SteamGame->playtime_2weeks = 0;
        }

        if (!empty($game->img_icon_url)) {
            $SteamGame->image_icon_url = $this->img_base_url.$SteamGame->id.'/'.$game->img_icon_url.'.jpg';
        }

        if (!empty($game->img_logo_url)) {
            $SteamGame->image_logo_url = $this->img_base_url.$SteamGame->id.'/'.$game->img_logo_url.'.jpg';
        }

        if (!empty($game->has_community_visible_stats)) {
            $SteamGame->has_community_visible_stats = $game->has_community_visible_stats;
        }

        $SteamGame->game_updated_at = date('Y-m-d H:i:s');
        $success = $SteamGame->save();
        if ($success === true) {
            Log::info('Game was updated - '.$SteamGame->name.' : '.$SteamGame->id);

            return true;
        }
        Log::error('Game was NOT updated - '.$SteamGame->name.' : '.$SteamGame->id);

        return false;
    }

    /**
     *
     */
    private function parseAndSaveOnlyOwnedGames()
    {
        $SteamOwnedGamesFromAPI = $this->getOwnedGames();
        foreach ($SteamOwnedGamesFromAPI as $game) {
            if (empty($game)) {
                continue;
            }
            $SteamGame = SteamGame::firstOrNew(['id' => $game->appid]);
            $this->parseAndSaveSteamGame($SteamGame, $game);
        }
    }

    /**
     * @param $SteamGame
     */
    private function runDescriptionSchemaAchievements($SteamGame)
    {
        new SteamAPIGameDescription($SteamGame);
        new SteamAPIGameSchema($SteamGame);
        new SteamAPIGameAchievements($SteamGame);
    }

    /**
     * @return mixed
     */
    public function getOwnedGames()
    {
        $localfile = 'SteamApi/GetOwnedGames.json';

        if (env('APP_ENV', false) == 'local' && $cachedAPICall = $this->getCachedAPICall($localfile)) {
            return $cachedAPICall;
        };

        $GetOwnedGames = new GetOwnedGames(env('STEAM_64ID', false));
        $GetOwnedGames = $this->steam->run($GetOwnedGames->setIncludeAppInfo(1));
        $GetOwnedGamesJson = json_encode($GetOwnedGames['response']['games']);

        $this->incrementSteamApiLimitCounter();

        if ((env('APP_ENV', false) == 'local')) {
            $this->saveCachedAPICall($localfile, $GetOwnedGamesJson);
        }

        return json_decode($GetOwnedGamesJson);
    }

    /**
     * @return mixed|void
     */
    public function getRecentlyPlayedGames()
    {
        $localfile = 'SteamApi/GetRecentlyPlayedGames.json';
        if (env('APP_ENV', false) == 'local' && $cachedAPICall = $this->getCachedAPICall($localfile)) {
            return $cachedAPICall;
        };
        $GetRecentlyPlayedGames = new GetRecentlyPlayedGames(env('STEAM_64ID', false));
        $GetRecentlyPlayedGames = $this->steam->run($GetRecentlyPlayedGames);

        if (empty($GetRecentlyPlayedGames['response']['games'])) {
            return;
        }

        $GetRecentlyPlayedGamesJson = json_encode($GetRecentlyPlayedGames['response']['games']);

        $this->incrementSteamApiLimitCounter();

        if ((env('APP_ENV', false) == 'local')) {
            $this->saveCachedAPICall($this->localfile, $GetRecentlyPlayedGamesJson);
        }

        return json_decode($GetRecentlyPlayedGamesJson);
    }
}
