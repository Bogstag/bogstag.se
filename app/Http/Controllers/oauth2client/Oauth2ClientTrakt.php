<?php

namespace App\Http\Controllers\oauth2client;

use Log;
use Carbon\Carbon;
use App\Oauth2Credential;
use Illuminate\Http\Request;

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
     * @param Request     $request
     * @param Oauth2Credential $credential
     *
     * @return \League\OAuth2\Client\Token\AccessToken
     */
    public function authorizeCredentials(Request $request, Oauth2Credential $credential)
    {
        if (empty($credential->redirecturi)) {
            $credential->redirecturi = $request->url();
        }

        $provider = $this->createProvider($credential);

        if (! empty($_GET['error'])) {
            abort(500, $_GET['error']);
        } elseif (empty($_GET['code'])) {
            $provider->authorize();
        }
        $token = $provider->getAccessToken('authorization_code', ['code' => $_GET['code']]);

        return $token;
    }

    /**
     * @param $credential
     *
     * @return \Bogstag\OAuth2\Client\Provider\Trakt
     */
    private function createProvider($credential)
    {
        $provider = new \Bogstag\OAuth2\Client\Provider\Trakt(
            [
                'clientId'     => $credential->clientid,
                'clientSecret' => $credential->clientsecret,
                'redirectUri'  => $credential->redirecturi,
            ]
        );

        return $provider;
    }

    public function refreshToken()
    {
        $now = Carbon::now();
        $this->getCredential();
        if ($now->diffInDays($this->credential->expires) < 14) {
            $provider = $this->getProvider();
            $newAccessToken = $provider->getAccessToken(
                'refresh_token',
                ['refresh_token' => $this->credential->refreshtoken]
            );
            $this->saveNewToken($newAccessToken, $this->credential);
            Log::info(
                'Token was updated for '.$this->credential->provider.' with new expiration of '.
                $this->credential->expires
            );
        }
    }

    private function getCredential()
    {
        $this->credential = Oauth2Credential::where('provider', 'Trakt')->firstOrFail();
    }

    /**
     * @return \Bogstag\OAuth2\Client\Provider\Trakt
     */
    private function getProvider()
    {
        $this->getCredential();
        $provider = $this->createProvider($this->credential);

        return $provider;
    }

    /**
     * @param \League\OAuth2\Client\Token\AccessToken $token
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
     * @param      string $method
     * @param      string $url
     * @param null $body
     *
     * @return mixed
     */
    public function createAuthRequest(
        $method, $url, $body = null
    ) {
        $this->getCredential();
        $options = [];
        if (! empty($body)) {
            $options = [
                'body' => $body,
            ];
        }
        $provider = $this->getProvider();
        $request = $provider->getAuthenticatedRequest($method, $url, $this->credential->accesstoken, $options);

        return $provider->getParsedResponse($request);
    }
}
