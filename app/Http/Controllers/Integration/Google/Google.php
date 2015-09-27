<?php

namespace App\Http\Controllers\Integration\Google;

use App\Http\Controllers\Integration\Integrator;

/**
 * Class Google
 * @package App\Http\Controllers\Integration\Google
 */
class Google extends Integrator
{

    /**
     * @param array $scopes
     */
    public function __construct($scopes = array())
    {
        $this->google_client = new \Google_Client();
        $this->google_client->setApplicationName('Api Project');
        $this->google_client->setClientId(env('GOOGLE_CLIENT_ID', false));
        $this->google_client->setClientSecret(env('GOOGLE_CLIENT_SECRET', false));
        $this->google_client->setState('offline');
        if ($this->google_client->isAccessTokenExpired()) {
            $this->google_client->refreshToken(env('GOOGLE_REFRESH_TOKEN', false));
        }
        return $this->google_client->setScopes($scopes);

    }
}
