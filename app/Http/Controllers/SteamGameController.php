<?php

namespace App\Http\Controllers;

use App\SteamGame;
use App\SteamGameDescription;
use Illuminate\Http\Request;

class SteamGameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @method void scopeListGames()
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $SteamGames = SteamGame::ListGames()->get();
        $averagePlaytimePerDay =
            round(
                (SteamGame::where('playtime_2weeks', '>', 0)
                    ->sum('playtime_2weeks')) / 60 / 14,
                2
            );

        return view(
            'pages.SteamGames',
            ['SteamGames' => $SteamGames, 'averageplaytimeperday' => $averagePlaytimePerDay]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $SteamGame = SteamGame::find($id);
        $CompletedAchievements = $SteamGame->achievements->filter(function ($item, $key) {
            return $item['value'] == 1;
        });
        $NotCompletedAchievements = $SteamGame->achievements->filter(function ($item, $key) {
            return $item['value'] == 0;
        });

        return view(
            'pages.SteamGame',
            [
                'SteamGame' => $SteamGame,
                'CompletedAchievements' => $CompletedAchievements,
                'NotCompletedAchievements' => $NotCompletedAchievements,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
