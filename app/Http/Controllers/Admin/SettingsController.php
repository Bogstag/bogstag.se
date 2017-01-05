<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;

/**
 * Class SettingsController.
 */
class SettingsController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ApiToken = Auth::user()->api_token;

        return view('admin.dashboard.Settings', ['ApiToken' => $ApiToken]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function resetapitoken(Request $request)
    {
        $user = User::where('api_token', $request->input('RefreshApiToken'))->first();
        $user->update(['api_token' => str_random(60)]);

        return view('admin.dashboard.Settings', ['ApiToken' => $user->api_token]);
    }
}
