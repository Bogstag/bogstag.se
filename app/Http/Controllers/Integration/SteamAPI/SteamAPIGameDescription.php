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
     * @param int $GameId
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
     * @return null|bool
     */
    public function updateSteamGameWithNewDescription(SteamGame $SteamGame, $GameDescriptionJson = null)
    {
        if (empty($GameDescriptionJson)) {
            return null;
        }

        $jsonModelMapping = [
            'is_free'                      => 'is_free',
            'about_the_game'               => 'about_the_game',
            'legal_notice'                 => 'legal_notice',
            'website'                      => 'website',
            'header_image'                 => 'image_header',
            'metacritic.score'             => 'meta_critic_score',
            'metacritic.url'               => 'meta_critic_url',
            "screenshots.0.path_thumbnail" => 'screenshot_path_thumbnail',
            "screenshots.0.path_full"      => 'screenshot_path_full',
            "movies.0.thumbnail"           => 'movie_thumbnail',
            "movies.0.webm.max"            => 'movie_full_url',
            "movies.0.name"                => 'movie_name',
            "background"                => 'Image_background',
        ];
        foreach ($jsonModelMapping as $key => $val) {
            $value = $this->getValueByKey($GameDescriptionJson, $key);
            if (isset($value)) {
                $SteamGame->{$val} = $value;
            }
        }

        $SteamGame->description_updated_at = date('Y-m-d H:i:s');

        return $SteamGame->save();
    }
}
