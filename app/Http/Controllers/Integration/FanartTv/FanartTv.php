<?php

namespace App\Http\Controllers\Integration\FanartTv;

use App\Image;
use App\Movie;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\Integration\Integrator;

/**
 * Class FanartTv.
 */
class FanartTv extends Integrator
{
    protected $externalApiLimit = 9999; //no hard limit

    protected $externalApiLimitInterval = 'Day';

    protected $externalApiName = 'FanartTvApi';

    protected $projectApiKey;

    protected $resource;

    protected $poster;

    protected $clearart;

    protected $storagepath = '/images/fanartimages/';

    protected $baseUrl = 'http://webservice.fanart.tv/v3/';

    protected $method = 'GET';

    public function getMovieImages(Movie $movie)
    {
        $this->setResource('movies');
        $this->setFanartid($movie->id_tmdb);
        $images = $this->parseImagesJson($this->makeRequest($movie), $movie);

        $images->each(function ($image) use ($movie) {
            $this->storeImageDatabase($movie, $image);
        });

        return true;
    }

    /**
     * @param mixed $fanartid
     */
    public function setFanartid($fanartid)
    {
        $this->fanartid = $fanartid;
    }

    private function parseImagesJson($imagesJson, $movie)
    {
        $imagesJson = collect($imagesJson);
        if ($this->resource == 'movies') {
            try {
                $poster = collect($imagesJson->get('movieposter'))->filter(function ($item) {
                    return $item->lang = 'en';
                })->first()->url;
                $collect1 = ['poster' => ['url' => $poster, 'type' => 'poster']];
            } catch (\ErrorException $e) {
                $collect1 = [];
                $movie->fanarttvpostermissing = 1;
                $movie->save();
            }
            try {
                $clearart = collect($imagesJson->get('hdmovieclearart'))->filter(function ($item) {
                    return $item->lang = 'en';
                })->first()->url;
                $collect2 = ['clearart' => ['url' => $clearart, 'type' => 'clearart']];
            } catch (\ErrorException $e) {
                $collect2 = [];
                $movie->fanarttvclearartmissing = 1;
                $movie->save();
            }
        }

        return collect(array_merge($collect1, $collect2));
    }

    private function makeRequest($movie)
    {
        $url = $this->getApiUrl();

        $localFile = 'FanartTv/'.urlencode($url);

        if (env('APP_ENV', false) == 'local' && $cachedAPICall = $this->getCachedAPICall($localFile)) {
            return $cachedAPICall;
        }
        try {
            $client = new Client();
            $response = $client->request('GET', $url);
            if ($response->getStatusCode() != 200) {
                return false;
            }
            $result = $response->getBody();
        } catch (RequestException $e) {
            $result = false;
            $movie->fanarttvmissing = 1;
            $movie->save();
        }

        $this->incrementTraktTvApiLimitCounter();

        if ((env('APP_ENV', false) == 'local')) {
            $this->saveCachedAPICall($localFile, $result);
        }

        return json_decode($result);
    }

    private function getApiUrl()
    {
        $this->projectApiKey = env('FANART_TV_API_KEY', false);
        $url = $this->baseUrl.$this->resource.'/'.$this->fanartid.'?api_key='.$this->projectApiKey;

        return $url;
    }

    private function incrementTraktTvApiLimitCounter()
    {
        $this->addExternalAPILimitCounter(Carbon::now(), $this->externalApiName, $this->externalApiLimit,
            $this->externalApiLimitInterval);
    }

    private function storeImageDatabase($movie, $image)
    {
        $movieImages = Image::firstOrNew(['orginalimage' => $image['url'], 'imagetype' => $image['type']]);
        $movieImages->imagepath = $this->getLocalFileName($movie->year, $movie->slug, $image['type'], $image['url']);
        if ($movieImages->exists() and Storage::disk('local')->exists($movieImages->imagepath)) {
            return false;
        }
        $movieImages->orginalimage = $image['url'];
        $movieImages->imagetype = $image['type'];
        if ($this->storeImage($image['url'], $movieImages->imagepath)) {
            return $movie->images()->save($movieImages);
        } else {
            return false;
        }
    }

    private function getLocalFileName(int $year, string $slug, string $imageType, string $url)
    {
        return $this->storagepath.$year.'/'.$slug.'-'.$imageType.'.'.substr($url, -3);
    }

    private function storeImage($url, $fileNameAndPath)
    {
        try {
            $client = new Client();
            $dirname = dirname($fileNameAndPath);
            if (! Storage::disk('public')->exists($dirname)) {
                Storage::disk('public')->makeDirectory($dirname);
            }
            Storage::disk('public')->put($fileNameAndPath, $client->request('GET', $url)->getBody());

            return true;
        } catch (Exception $e) {
            // Log the error or something
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param mixed $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return mixed
     */
    public function getFanartid()
    {
        return $this->fanartid;
    }
}
