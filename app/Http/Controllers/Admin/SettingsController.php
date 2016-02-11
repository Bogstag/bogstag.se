<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
//use App\Http\Requests;
use App\Http\Controllers\AdminController;
use Auth;
use App\User;

/**
 * Class SettingsController
 * @package App\Http\Controllers\Admin
 */
class SettingsController extends AdminController
{

    /**
     * SettingsController constructor.
     */
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Settings";
        $ApiToken = Auth::user()->api_token;

        return view('admin.dashboard.Settings', compact('title', 'ApiToken'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resetapitoken(Request $request)
    {
        $user = User::where('api_token', $request->input('RefreshApiToken'))->first();
        $user->api_token = str_random(60);
        $user->save();
        return view('admin.dashboard.Settings', ['title' => 'Settings', 'ApiToken' => $user->api_token]);
    }
}
