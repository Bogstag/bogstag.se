<?php

namespace App\Http\Controllers\Integration\TraktTv;

use App\Http\Controllers\Integration\Integrator;
use App\Http\Controllers\oauth2client\Oauth2ClientTrakt;
use App\Movie;
use Carbon\Carbon;

class TraktTv extends Integrator
{
    /**
     * @var int
     */
    protected $externalApiLimit = 1000;
    /**
     * @var string
     */
    protected $externalApiLimitInterval = 'Day';
    /**
     * @var string
     */
    protected $externalApiName = 'TraktTvApi';
    protected $traktClient;
    protected $method = 'GET';
    protected $baseUrl = 'https://api-v2launch.trakt.tv/';
    protected $limit = null;
    protected $urlPart = null;
    protected $type = null;
    protected $extended = null;

    public function __construct()
    {
        $this->traktClient = new Oauth2ClientTrakt();
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function setType($type)
    {
        $this->type = $type.'/';
    }

    public function setExtended($extended)
    {
        $this->extended = $extended;
    }

    public function loadWatched()
    {
        $watchedMovies = $this->getSyncWatched();

        foreach ($watchedMovies as $watchedMovie) {
            $this->storeMovie($watchedMovie);
        }

        return $watchedMovies;
    }

    public function getSyncWatched()
    {
        $this->urlPart = 'sync/watched/';

        return $this->makeRequest();
    }

    private function makeRequest()
    {
        $url = $this->createUrl();
        $method = $this->method;
        $localFile = 'Trakt/'.urlencode($method.$url);

        if (env('APP_ENV', false) == 'local' && $cachedAPICall = $this->getCachedAPICall($localFile)) {
            return $cachedAPICall;
        };

        $result = $this->traktClient->createAuthRequest(
            $method,
            $url
        );

        $this->incrementTraktTvApiLimitCounter();

        if ((env('APP_ENV', false) == 'local')) {
            $this->saveCachedAPICall($localFile, json_encode($result));
        }

        return collect($result);
    }

    private function createUrl()
    {
        $url = $this->baseUrl.$this->urlPart.$this->type;
        $parameters = null;
        if (!empty($this->limit)) {
            $parameters .= 'limit='.$this->limit.'&';
        }

        if (!empty($this->extended)) {
            $parameters .= 'extended='.$this->extended.'&';
        }

        if (!empty($parameters)) {
            return $url.'?'.$parameters;
        }

        return $url;
    }

    private function incrementTraktTvApiLimitCounter()
    {
        $this->addExternalAPILimitCounter(
            Carbon::now(),
            $this->externalApiName,
            $this->externalApiLimit,
            $this->externalApiLimitInterval
        );
    }

    private function storeMovie($watchedMovie)
    {
        dd($watchedMovie);
        $movie = Movie::firstOrNew(['id_trakt' => $watchedMovie->movie->ids->trakt]);

        if (!empty($watchedMovie->plays)) {
            $movie->plays = $watchedMovie->plays;
        }

        if (!empty($watchedMovie->watched_at)) {
            $movie->last_watched_at = new Carbon($watchedMovie->watched_at);
        } elseif (!empty($watchedMovie->last_watched_at)) {
            $movie->last_watched_at = new Carbon($watchedMovie->last_watched_at);
        }

        $movie->title = $watchedMovie->movie->title;
        $movie->year = $watchedMovie->movie->year;
        $movie->slug = $watchedMovie->movie->ids->slug;
        $movie->id_imdb = $watchedMovie->movie->ids->imdb;
        $movie->id_tmdb = $watchedMovie->movie->ids->tmdb;
        $movie->tagline = $watchedMovie->movie->tagline;
        $movie->overview = $watchedMovie->movie->overview;
        $movie->released = $watchedMovie->movie->released;
        $movie->runtime = $watchedMovie->movie->runtime;
        $movie->trailer = $watchedMovie->movie->trailer;
        $movie->homepage = $watchedMovie->movie->homepage;
        $movie->trakt_updated_at = new Carbon($watchedMovie->movie->updated_at);
        $movie->certification = $watchedMovie->movie->certification;
        $movie->fanart = $watchedMovie->movie->images->fanart->full;
        $movie->poster = $watchedMovie->movie->images->poster->full;
        $movie->logo = $watchedMovie->movie->images->logo->full;
        $movie->clearart = $watchedMovie->movie->images->clearart->full;
        $movie->banner = $watchedMovie->movie->images->banner->full;
        $movie->thumb = $watchedMovie->movie->images->thumb->full;
        $movie->genres = $watchedMovie->movie->genres;

        return $movie->save();
    }

    public function syncWatched($command)
    {
        $watchedMovies = $this->getSyncHistory();
        foreach ($watchedMovies as $watchedMovie) {
            $this->storeMovie($watchedMovie);
            $command->info('Stored '.$watchedMovie->movie->title.' to db');
        }

        return $watchedMovies;
    }

    public function getSyncHistory()
    {
        $this->urlPart = 'sync/history/';

        return $this->makeRequest();
    }
}
