<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\oauth2client\Oauth2ClientTrakt;
use App\Oauth2Credential;
use Illuminate\Http\Request;

/**
 * Class Oauth2CredentialAdminController.
 */
class Oauth2CredentialAdminController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $title = 'Oauth2 Credentials';
        $oauth2Credentials = Oauth2Credential::all();

        return view('admin.dashboard.oauth2credential.oauth2credentialspage', compact('title', 'oauth2Credentials'));
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $sanitizedProvider = strip_tags($input['provider']);
        $credential = Oauth2Credential::firstOrNew(
            ['provider' => $sanitizedProvider]
        );
        $credential->clientid = $input['clientid'];
        $credential->clientsecret = $input['clientsecret'];
        $credential->save();

        return redirect()->action('Admin\Oauth2CredentialAdminController@show', $sanitizedProvider);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Request $request)
    {
        $credential = Oauth2Credential::firstOrNew(
            ['provider' => $request->route('oauth2credential')]
        );

        if ($request->route('oauth2credential') == 'Trakt') {
            $Oauth2Client = new Oauth2ClientTrakt();
        } else {
            return redirect()->action('Admin\Oauth2CredentialAdminController@index');
        }

        $token = $Oauth2Client->authorizeCredentials($request, $credential);
        $credential->accesstoken = $token->getToken();
        $credential->expires = $token->getExpires();
        $credential->refreshtoken = $token->getRefreshToken();
        $credential->redirecturi = $request->url();
        $credential->save();

        return redirect()->action('Admin\Oauth2CredentialAdminController@index');
    }
}
