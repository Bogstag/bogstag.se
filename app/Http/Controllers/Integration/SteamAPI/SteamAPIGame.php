<?php

namespace App\Http\Controllers\Integration\SteamAPI;

use App\SteamGame;
use App\SteamGameAchievement;
use App\SteamGameStat;

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
            $this->parseAndSaveSteamGame($game);
            $this->parseAndSaveDescription($game->appid);
            $this->parseAndSaveSchema($game->appid);
            $this->parseAndSaveAchievementsAndStats($game->appid);
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
        if ($gameId == null) {
            return 'No game id specified';
        }
        $SteamOwnedGamesFromAPI = $this->getOwnedGames();
        foreach ($SteamOwnedGamesFromAPI as $game) {
            if ($gameId == $game->appid) {
                break;
            }
        }
        $this->parseAndSaveSteamGame($game);
        $this->parseAndSaveDescription($gameId);
        $this->parseAndSaveSchema($gameId);
        $this->parseAndSaveAchievementsAndStats($gameId);
    }

    /**
     * Not really needed to run this.
     * You can use it as a slow update of your db if you like
     * updated screenshots and movies and achievements.
     */
    public function updateGames()
    {
        $this->parseAndSaveOnlyOwnedGames();
        $this->parseAndSaveDescription(SteamGame::DescriptionNeedUpdate()->pluck('id')->first());
        $this->parseAndSaveSchema(SteamGame::SchemaNeedUpdate()->pluck('id')->first());
        $this->parseAndSaveAchievementsAndStats(SteamGame::AchievementsNeedUpdate()->pluck('id')->first());
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
            $this->parseAndSaveSteamGame($game);
            $this->parseAndSaveDescription($game->appid);
            $this->parseAndSaveSchema($game->appid);
            $this->parseAndSaveAchievementsAndStats($game->appid);
        }
    }

    /**
     * @param $GameId
     */
    private function parseAndSaveDescription($GameId)
    {
        if (!$GameId) {
            return;
        }
        $Description = $this->getSteamGameDescriptionFromUrl($GameId);
        if (empty($Description)) {
            return;
        }

        $SteamGame = SteamGame::find($GameId);

        if (!empty($Description->is_free)) {
            $SteamGame->is_free = $Description->is_free;
        }

        if (!empty($Description->about_the_game)) {
            $SteamGame->about_the_game = $Description->about_the_game;
        }

        if (!empty($Description->header_image)) {
            $SteamGame->image_header = $Description->header_image;
        }

        if (!empty($Description->legal_notice)) {
            $SteamGame->legal_notice = $Description->legal_notice;
        }

        if (!empty($Description->metacritic->score)) {
            $SteamGame->meta_critic_score = $Description->metacritic->score;
        }

        if (!empty($Description->metacritic->url)) {
            $SteamGame->meta_critic_url = $Description->metacritic->url;
        }

        if (!empty($Description->website)) {
            $SteamGame->website = $Description->website;
        }

        if (!empty($Description->screenshots{0}->path_thumbnail)) {
            $SteamGame->screenshot_path_thumbnail = $Description->screenshots{0}
            ->path_thumbnail;
        }

        if (!empty($Description->screenshots{0}->path_full)) {
            $SteamGame->screenshot_path_full = $Description->screenshots{0}
            ->path_full;
        }
        if (!empty($Description->movies)) {
            $lastMovie = end($Description->movies);
            if (!empty($lastMovie->thumbnail)) {
                $SteamGame->movie_thumbnail = $lastMovie->thumbnail;
            }

            if (!empty($lastMovie->webm->max)) {
                $SteamGame->movie_full_url = $lastMovie->webm->max;
            }

            if (!empty($lastMovie->name)) {
                $SteamGame->movie_name = $lastMovie->name;
            }
        }
        if (!empty($Description->background)) {
            $SteamGame->image_background = $Description->background;
        }

        $SteamGame->description_updated_at = date('Y-m-d H:i:s');

        $SteamGame->save();
    }

    /**
     * @param $GameId
     */
    private function parseAndSaveSchema($GameId)
    {
        if (!$GameId || in_array($GameId, SteamGame::getGamesWithNoStats())) {
            return;
        }
        $SteamGameSchemaFromAPI = $this->getSchemaForGame($GameId);
        if (empty($SteamGameSchemaFromAPI)) {
            return;
        }
        if (!empty($SteamGameSchemaFromAPI->achievements)) {
            $this->parseAndSaveAchievementSchema($GameId, $SteamGameSchemaFromAPI->achievements);
        }
        if (!empty($SteamGameSchemaFromAPI->stats)) {
            $this->parseAndSaveStatSchema($GameId, $SteamGameSchemaFromAPI->stats);
        }
        if (!empty($SteamGameSchemaFromAPI->achievements) || !empty($SteamGameSchemaFromAPI->stats)) {
            $this->setGameTimestampForSchema($GameId);
        }
    }

    /**
     * @param $GameId
     * @param $SteamGameSchemaFromAPI
     */
    private function parseAndSaveAchievementSchema($GameId, $SteamGameSchemaFromAPI)
    {
        if (!$GameId) {
            return;
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
    }

    /**
     * @param $GameId
     * @param $SteamGameSchemaFromAPI
     */
    private function parseAndSaveStatSchema($GameId, $SteamGameSchemaFromAPI)
    {
        if (!$GameId) {
            return;
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
        $SteamGame->save();
    }

    /**
     * @param $GameId
     */
    private function parseAndSaveAchievementsAndStats($GameId)
    {
        if (!$GameId || in_array($GameId, SteamGame::getGamesWithNoStats())) {
            return;
        }
        $SteamGameAchievementsFromAPI = $this->getUserStatsForGame($GameId);
        if (empty($SteamGameAchievementsFromAPI)) {
            return;
        }
        if (!empty($SteamGameAchievementsFromAPI->achievements)) {
            $this->parseAndSaveAchievements($GameId, $SteamGameAchievementsFromAPI->achievements);
        }
        if (!empty($SteamGameAchievementsFromAPI->stats)) {
            $this->parseAndSaveStats($GameId, $SteamGameAchievementsFromAPI->stats);
        }
        if (!empty($SteamGameAchievementsFromAPI->achievements) || !empty($SteamGameAchievementsFromAPI->stats)) {
            $this->setGameTimestampForAchievement($GameId);
        }
    }

    /**
     * @param $GameId
     * @param $SteamGameAchievementsFromAPI
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

            if (!empty($achievement->achieved)) {
                $SteamAchievement->value = $achievement->achieved;
            }

            $SteamAchievement->save();
        }
    }

    /**
     * @param $GameId
     * @param $SteamGameAchievementsFromAPI
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

            if (!empty($stat->value)) {
                $SteamStat->value = $stat->value;
            }
            $SteamStat->save();
        }
    }

    /**
     * @param $GameId
     */
    private function setGameTimestampForAchievement($GameId)
    {
        $SteamGame = SteamGame::find($GameId);
        $SteamGame->player_stats_updated_at = date('Y-m-d H:i:s');
        $SteamGame->save();
    }

    /**
     * @param $game
     */
    private function parseAndSaveSteamGame($game)
    {
        $SteamGame = SteamGame::firstOrNew(['id' => $game->appid]);

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
        $SteamGame->save();
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

            $this->parseAndSaveSteamGame($game);
        }
    }
}
