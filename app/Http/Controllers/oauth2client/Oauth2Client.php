<?php

namespace App\Http\Controllers\oauth2client;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Oauth2Credential;

/**
 * Class Oauth2Client
 * @package App\Http\Controllers\oauth2client
 */
abstract class Oauth2Client extends Controller
{
    /**
     * @param Request $request
     * @param Oauth2Credential $credential
     * @return mixed
     */
    abstract public function authorizeCredentials(Request $request, Oauth2Credential $credential);

    /**
     * @return mixed
     */
    abstract public function refreshToken();
}
