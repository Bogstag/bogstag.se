<?php

namespace App\Http\Controllers\oauth2client;

use App\Http\Controllers\Controller;
use App\Oauth2Credential;
use Illuminate\Http\Request;

/**
 * Class Oauth2Client.
 */
abstract class Oauth2Client extends Controller
{
    /**
     * @param Request          $request
     * @param Oauth2Credential $credential
     *
     * @return mixed
     */
    abstract public function authorizeCredentials(Request $request, Oauth2Credential $credential);

    /**
     * @return mixed
     */
    abstract public function refreshToken();
}
