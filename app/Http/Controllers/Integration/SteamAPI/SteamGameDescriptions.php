<?php

namespace App\Http\Controllers\Integration\SteamAPI;

use App\SteamGameDescription;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Log;

class SteamGameDescriptions extends SteamAPI
{
    public function updateSteamGameDescription()
    {
        $GameIds = DB::table('steam_games')
            ->leftJoin('steam_game_descriptions', 'steam_games.id', '=', 'steam_game_descriptions.id')
            ->select('steam_games.id')
            ->where('steam_games.playtimeforever', '>', 0)
            ->where(function (Builder $query) {
                $query->where('steam_game_descriptions.updated_at', '<', date('Y-m-d'))
                    ->orWhere('steam_game_descriptions.updated_at', null);
            })->orderBy('steam_game_descriptions.updated_at', 'asc')
            ->lists('steam_games.id');
        if (empty($GameIds)) {
            Log::info(date('Y-m-d H:i:s').' No more descriptions to update');
        } else {
            foreach ($GameIds as $GameId) {
                $this->getSteamGameDescription($GameId);
                if (empty($this->GameDescriptionJson->$GameId->data)) {
                    continue;
                } else {
                    $this->parseAndSaveDescription($this->GameDescriptionJson->$GameId->data);
                }
            }
        }
    }

    private function getSteamGameDescription($GameId)
    {
        $url = 'http://store.steampowered.com/api/appdetails?appids='.$GameId;
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, $url);
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

        $this->GameDescriptionJson = json_decode(curl_exec($curlSession));
        curl_close($curlSession);
    }

    private function parseAndSaveDescription($Description)
    {
        $SteamDescription = SteamGameDescription::where('id', $Description->steam_appid)->first();
        if ($SteamDescription === null) {
            $SteamDescription = new SteamGameDescription();
            $SteamDescription->id = $Description->steam_appid;
        }

        if (isset($Description->name)) {
            $SteamDescription->name = $Description->name;
        }

        if (isset($Description->is_free)) {
            $SteamDescription->is_free = $Description->is_free;
        }

        if (isset($Description->about_the_game)) {
            $SteamDescription->about = $Description->about_the_game;
        }

        if (isset($Description->header_image)) {
            $SteamDescription->header_image = $Description->header_image;
        }

        if (isset($Description->legal_notice)) {
            $SteamDescription->legal_notice = $Description->legal_notice;
        }

        if (isset($Description->metacritic->score)) {
            $SteamDescription->meta_critic_score = $Description->metacritic->score;
        }

        if (isset($Description->metacritic->url)) {
            $SteamDescription->meta_critic_url = $Description->metacritic->url;
        }

        if (isset($Description->website)) {
            $SteamDescription->website = $Description->website;
        }

        if (isset($Description->screenshots{0}->path_thumbnail)) {
            $SteamDescription->screenshot_thumbnail = $Description->screenshots{0}
                ->path_thumbnail;
        }

        if (isset($Description->screenshots{0}->path_full)) {
            $SteamDescription->screenshot_full = $Description->screenshots{0}
                ->path_full;
        }
        if (isset($Description->movies)) {
            $lastMovie = end($Description->movies);
            if (isset($lastMovie->thumbnail)) {
                $SteamDescription->movie_thumbnail = $lastMovie->thumbnail;
            }

            if (isset($lastMovie->webm->max)) {
                $SteamDescription->movie_full = $lastMovie->webm->max;
            }

            if (isset($lastMovie->name)) {
                $SteamDescription->movie_name = $lastMovie->name;
            }
        }
        if (isset($Description->background)) {
            $SteamDescription->background = $Description->background;
        }
        $SteamDescription->save();
    }
}
