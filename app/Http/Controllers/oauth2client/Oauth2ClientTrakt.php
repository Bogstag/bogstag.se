<?php

namespace App\Http\Controllers\oauth2client;

use App\Oauth2Credential;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Log;

/**
 * Class Oauth2ClientTrakt.
 */
class Oauth2ClientTrakt extends Oauth2Client
{
    /**
     * @var
     */
    protected $credential;

    /**
     * Oauth2ClientTrakt constructor.
     */
    public function __construct()
    {
        $this->credential = Oauth2Credential::where('provider', 'Trakt')->firstOrFail();
    }

    /**
     * @param Request     $request
     * @param array|mixed $credential
     *
     * @return \League\OAuth2\Client\Token\AccessToken
     */
    public function authorizeCredentials(Request $request, Oauth2Credential $credential)
    {
        if (empty($credential->redirecturi)) {
            $credential->redirecturi = $request->url();
        }

        $provider = $this->createProvider($credential);

        if (!empty($_GET['error'])) {
            exit('Got error: '.$_GET['error']);
        } elseif (empty($_GET['code'])) {
            $provider->authorize();
        }
        $token = $provider->getAccessToken('authorization_code', ['code' => $_GET['code']]);

        return $token;
    }

    /**
     * @param $credential
     *
     * @return Trakt
     */
    private function createProvider($credential)
    {
        $provider = new Trakt(
            ['clientId'     => $credential->clientid,
             'clientSecret' => $credential->clientsecret,
             'redirectUri'  => $credential->redirecturi,
             'hostedDomain' => env('APP_URL', null), ]
        );

        return $provider;
    }

    public function refreshToken()
    {
        $now = Carbon::now();
        if ($now->diffInDays($this->credential->expires) < 14) {
            $provider = $this->getProvider();
            $newAccessToken = $provider->getAccessToken(
                'refresh_token',
                ['refresh_token' => $this->credential->refreshtoken]
            );
            $this->saveNewToken($newAccessToken, $this->credential);
            Log::info(
                'Token was updated for '.$this->credential->provider.' with new expiration of '.$this->credential->expires
            );
        }
    }

    /**
     * @return Trakt
     */
    private function getProvider()
    {
        $provider = $this->createProvider($this->credential);

        return $provider;
    }

    /**
     * @param $token
     * @param $credential
     */
    private function saveNewToken($token, $credential)
    {
        $credential->accesstoken = $token->getToken();
        $credential->expires = $token->getExpires();
        $credential->refreshtoken = $token->getRefreshToken();
        $credential->save();
    }

    /**
     * @param $method
     * @param $url
     *
     * @return mixed
     */
    public function createAuthRequest(
        $method,
        $url
    ) {
        $credential = Oauth2Credential::where('provider', 'Trakt')->firstOrFail();
        $options = ['headers' => ['Content-Type'      => 'application/json',
                                  'trakt-api-version' => 2,
                                  'trakt-api-key'     => $credential->clientid, ]];
        $provider = $this->getProvider();
        $request = $provider->getAuthenticatedRequest($method, $url, $this->credential->accesstoken, $options);

        return $provider->getResponse($request);
    }
}
