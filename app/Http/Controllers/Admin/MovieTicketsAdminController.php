<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Movie;
use Illuminate\Http\Request;

/**
 * Class Oauth2CredentialAdminController.
 */
class MovieTicketsAdminController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $title = 'Movie Tickets';
        $tickets = Movie::select('title', 'slug', 'year')->where('ticket_price', null)->orderby('id', 'desc')->get();

        return view('admin.dashboard.movietickets.movieticketspage', compact('title', 'tickets'));
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $movie = Movie::where('slug', $input['slug'])->first();
        $movie->ticket_datetime = $input['ticket_datetime'];
        $movie->ticket_price = $input['ticket_price'];
        $movie->ticket_row = $input['ticket_row'];
        $movie->ticket_seat = $input['ticket_seat'];
        $destinationFile = $input['slug'].'.png';
        if (env('APP_ENV', false) == 'local') {
            $destinationPath = public_path('img\\tickets\\'.$input['year'].'\\');
        } else {
            $destinationPath = public_path('img/tickets/'.$input['year'].'/');
        }
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        file_put_contents($destinationPath.$destinationFile, file_get_contents($input['ticket_image']));
        $test = getimagesize($destinationPath.$destinationFile);
        if ($test[0] > 10) {
            $movie->save();
        }

        return redirect()->action('Admin\MovieTicketsAdminController@index');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Request $request)
    {
    }
}
