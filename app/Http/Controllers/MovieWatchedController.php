<?php

namespace App\Http\Controllers;

use App\Movie;

class MovieWatchedController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @method void scopeListGames()
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAll()
    {
        $WatchedMovies = Movie::WatchedMovies()->orderBy('last_watched_at', 'desc')->paginate(100);
        $title = 'All movies I have watched.';
        $ticketsview = false;

        return view('pages.Movie.Watched',
            ['WatchedMovies' => $WatchedMovies, 'title' => $title, 'ticketsview' => $ticketsview]);
    }


    public function indexCinema()
    {
        $WatchedMovies = Movie::WatchedMovies()->whereNotNull('ticket_datetime')->orderBy('ticket_datetime',
            'desc')->paginate(100);
        $title = 'Latest movies I have watched in the cinemas (with ticket).';
        $ticketsview = true;

        return view('pages.Movie.Watched',
            ['WatchedMovies' => $WatchedMovies, 'title' => $title, 'ticketsview' => $ticketsview]);
    }
}
