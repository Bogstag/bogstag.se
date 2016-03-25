<?php

namespace App\Http\Controllers;

use App\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
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
        $WatchedMovies = Movie::WatchedMovies()->paginate(100);
        $title = 'All movies i have watched.';
        return view(
            'pages.WatchedMovies',
            ['WatchedMovies' => $WatchedMovies, 'title' => $title]
        );
    }

    public function indexCinema()
    {
        $WatchedMovies = Movie::WatchedMovies()->whereNotNull('ticket_datetime')->paginate(100);
        $title = 'Latest movies i have watched in the cinemas (with ticket).';
        return view(
            'pages.WatchedMovies',
            ['WatchedMovies' => $WatchedMovies, 'title' => $title]
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

    public function show($slug)
    {
        $movie = Movie::where('slug', $slug)->first();
        $genrerow = '';
        foreach ($movie->genres as $genre) {
            $genrerow .= ucfirst($genre).', ';
        }
        $genrerow = rtrim($genrerow, ', ');

        return view('pages.Movie', ['movie' => $movie, 'genrerow' => $genrerow]);
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
