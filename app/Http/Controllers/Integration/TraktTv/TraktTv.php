<?php

namespace App\Http\Controllers\Integration\TraktTv;

use App\Movie;
use Carbon\Carbon;
use App\Http\Controllers\Integration\Integrator;
use App\Http\Controllers\Integration\FanartTv\FanartTv;
use App\Http\Controllers\oauth2client\Oauth2ClientTrakt;

/**
 * Class TraktTv.
 */
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

    protected $baseUrl = 'https://api.trakt.tv/';

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

    public function loadWatched()
    {
        $watchedMovies = $this->getSyncWatched();

        foreach ($watchedMovies as $watchedMovie) {
            $movie = $this->storeMovie($watchedMovie);
            $image = new FanartTv();
            $image->getMovieImages($movie);
        }

        return $watchedMovies;
    }

    public function getSyncWatched()
    {
        $this->urlPart = 'sync/watched/';

        return $this->makeRequest();
    }

    private function makeRequest(array $body = [])
    {
        $url = $this->createUrl();
        $method = $this->method;
        $localFile = 'Trakt/'.urlencode($method.$url);

        if (env('APP_ENV', false) == 'local' && $cachedAPICall = $this->getCachedAPICall($localFile)) {
            return $cachedAPICall;
        }

        $result = json_encode($this->traktClient->createAuthRequest($method, $url, $body));

        $this->incrementTraktTvApiLimitCounter();

        if ((env('APP_ENV', false) == 'local')) {
            $this->saveCachedAPICall($localFile, $result);
        }

        return json_decode($result);
    }

    private function createUrl()
    {
        $url = $this->baseUrl.$this->urlPart.$this->type;
        $parameters = null;
        if (! empty($this->limit)) {
            $parameters .= 'limit='.$this->limit.'&';
        }

        if (! empty($this->extended)) {
            $parameters .= 'extended='.$this->extended.'&';
        }

        if (! empty($parameters)) {
            return $url.'?'.$parameters;
        }

        return $url;
    }

    private function incrementTraktTvApiLimitCounter()
    {
        $this->addExternalAPILimitCounter(
            Carbon::now(), $this->externalApiName, $this->externalApiLimit, $this->externalApiLimitInterval
        );
    }

    /**
     * @return Movie
     */
    private function storeMovie($watchedMovie)
    {
        $movie = Movie::firstOrNew(['id_trakt' => $watchedMovie->movie->ids->trakt]);

        if (! empty($watchedMovie->plays)) {
            $movie->plays = $watchedMovie->plays;
        } else {
            $movie->plays = 0;
        }

        if (! empty($watchedMovie->watched_at)) {
            $last_watched_at = new Carbon($watchedMovie->watched_at);
            $last_watched_at->timezone = new \DateTimeZone(config('app.timezone'));
            $movie->last_watched_at = $last_watched_at;
        } elseif (! empty($watchedMovie->last_watched_at)) {
            $last_watched_at = new Carbon($watchedMovie->last_watched_at);
            $last_watched_at->timezone = new \DateTimeZone(config('app.timezone'));
            $movie->last_watched_at = $last_watched_at;
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
        $movie->genres = $watchedMovie->movie->genres;
        $movie->save();

        return $movie;
    }

    public function getMovie($id)
    {
        $this->urlPart = '';
        $this->setType('/movies/'.$id);
        $this->setExtended('full');
        $moviecollection = new \stdClass;
        $moviecollection->movie = $this->makeRequest();
        $movie = $this->storeMovie($moviecollection);
        $image = new FanartTv();
        $image->getMovieImages($movie);

        return $movie;
    }

    /**
     * @param string|null $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param string $extended
     */
    public function setExtended($extended)
    {
        $this->extended = $extended;
    }

    public function syncWatched($command)
    {
        $watchedMovies = $this->getSyncHistory();
        foreach ($watchedMovies as $watchedMovie) {
            $movie = $this->storeMovie($watchedMovie);
            $command->info('Stored '.$watchedMovie->movie->title.' to db');
            $image = new FanartTv();
            $image->getMovieImages($movie);
        }

        return $watchedMovies;
    }

    public function getSyncHistory()
    {
        $this->urlPart = 'sync/history/';

        return $this->makeRequest();
    }

    public function addSyncHistory($id, $watched_at)
    {
        $this->urlPart = 'sync/history/';
        $this->setType(null);
        $this->setMethod('POST');
        $body = json_encode(['movies' => [0 => ['watched_at' => $watched_at, 'ids' => ['imdb' => $id]]]]);

        return $this->makeRequest($body);
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }
}
