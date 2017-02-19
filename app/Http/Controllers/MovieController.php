<?php

namespace App\Http\Controllers;

use App\Movie;

class MovieController extends Controller
{
    public function show($slug)
    {
        $movie = Movie::where('slug', $slug)->first();
        if (! $movie) {
            abort(404, 'Movie do not exists, either i messed up or you write poorly');
        }
        $genrerow = '';
        foreach ($movie->genres as $genre) {
            $genrerow .= ucfirst($genre).', ';
        }
        $genrerow = rtrim($genrerow, ', ');

        return view('pages.Movie.Movie', ['movie' => $movie, 'genrerow' => $genrerow]);
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('pages.home');
    }
}
