<?php

namespace App\Http\Controllers;

use App\SteamGame;

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
                'SteamGame'                => $SteamGame,
                'CompletedAchievements'    => $CompletedAchievements,
                'NotCompletedAchievements' => $NotCompletedAchievements,
            ]
        );
    }
}
