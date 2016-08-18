<?php

namespace App\Http\Controllers\Integration\SteamAPI;

use App\SteamGame;
use Carbon\Carbon;
use Log;

/**
 * Class SteamAPIGameDescription.
 */
class SteamAPIGameDescription extends SteamAPIGame
{
    /**
     * @var string
     */
    private $localfile;
    /**
     * @var bool|mixed
     */
    private $GameDescriptionJson;
    /**
     * @var SteamGame
     */
    private $SteamGame;

    /**
     * @var bool
     */
    private $success = false;

    /**
     * SteamAPIGameDescription constructor.
     *
     * @param SteamGame $SteamGame
     */
    public function __construct(SteamGame $SteamGame)
    {
        parent::__construct();
        $this->SteamGame = $SteamGame;
        $this->localfile = 'SteamApi/SteamApiAppDetails/'.$this->SteamGame->id.'.json';
        $this->GameDescriptionJson = $this->getSteamGameDescriptionFromUrl($this->SteamGame->id);
        $this->success = $this->updateSteamGameWithNewDescription($this->SteamGame, $this->GameDescriptionJson);
        if ($this->success === true) {
            Log::info('Description was updated for '.$this->SteamGame->name.' : '.$this->SteamGame->id);
        } else {
            Log::error('Description was NOT updated for '.$this->SteamGame->name.' : '.$this->SteamGame->id);
        }

        return true;
    }

    /**
     * @param $GameId
     *
     * @return bool|mixed
     */
    public function getSteamGameDescriptionFromUrl($GameId)
    {
        if (env('APP_ENV', false) == 'local' && $cachedAPICall = $this->getCachedAPICall($this->localfile)) {
            return $cachedAPICall;
        }

        $url = 'http://store.steampowered.com/api/appdetails?appids='.$GameId;
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, $url);
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

        $GameDescriptionJson = json_decode(curl_exec($curlSession));

        if (empty($GameDescriptionJson->$GameId->data)) {
            return false;
        }
        $GameDescriptionJson = json_encode($GameDescriptionJson->$GameId->data);
        curl_close($curlSession);

        $this->addExternalAPILimitCounter(Carbon::now(), 'SteamApiAppDetails', 200, '5min');

        if ((env('APP_ENV', false) == 'local')) {
            $this->saveCachedAPICall($this->localfile, $GameDescriptionJson);
        }

        return json_decode($GameDescriptionJson);
    }

    /**
     * @param SteamGame $SteamGame
     * @param null      $GameDescriptionJson
     *
     * @return bool|void
     */
    private function updateSteamGameWithNewDescription(SteamGame $SteamGame, $GameDescriptionJson = null)
    {
        if (empty($GameDescriptionJson)) {
            return;
        }

        if (!empty($GameDescriptionJson->is_free)) {
            $SteamGame->is_free = $GameDescriptionJson->is_free;
        }

        if (!empty($GameDescriptionJson->about_the_game)) {
            $SteamGame->about_the_game = $GameDescriptionJson->about_the_game;
        }

        if (!empty($GameDescriptionJson->header_image)) {
            $SteamGame->image_header = $GameDescriptionJson->header_image;
        }

        if (!empty($GameDescriptionJson->legal_notice)) {
            $SteamGame->legal_notice = $GameDescriptionJson->legal_notice;
        }

        if (!empty($GameDescriptionJson->metacritic->score)) {
            $SteamGame->meta_critic_score = $GameDescriptionJson->metacritic->score;
        }

        if (!empty($GameDescriptionJson->metacritic->url)) {
            $SteamGame->meta_critic_url = $GameDescriptionJson->metacritic->url;
        }

        if (!empty($GameDescriptionJson->website)) {
            $SteamGame->website = $GameDescriptionJson->website;
        }

        if (!empty($GameDescriptionJson->screenshots[0]->path_thumbnail)) {
            $SteamGame->screenshot_path_thumbnail = $GameDescriptionJson->screenshots[0]
            ->path_thumbnail;
        }

        if (!empty($GameDescriptionJson->screenshots[0]->path_full)) {
            $SteamGame->screenshot_path_full = $GameDescriptionJson->screenshots[0]
            ->path_full;
        }
        if (!empty($GameDescriptionJson->movies)) {
            $lastMovie = end($GameDescriptionJson->movies);
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
        if (!empty($GameDescriptionJson->background)) {
            $SteamGame->image_background = $GameDescriptionJson->background;
        }

        $SteamGame->description_updated_at = date('Y-m-d H:i:s');

        return $SteamGame->save();
    }
}
