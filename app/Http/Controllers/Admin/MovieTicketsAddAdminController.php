<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Integration\TraktTv\TraktTv;
use Illuminate\Http\Request;

/**
 * Class Oauth2CredentialAdminController.
 */
class MovieTicketsAddAdminController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $title = 'Add Movie Tickets';

        return view('admin.dashboard.movietickets.addmovieticketspage', compact('title', 'tickets'));
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $trakt = new TraktTv();
        $movie = $trakt->getMovie($input['imdbid']);
        $movie->ticket_datetime = $input['ticket_datetime'].':00';
        if (($movie->last_watched_at = '0000-00-00 00:00:00') || ($movie->ticket_datetime > $movie->last_watched_at)) {
            $movie->last_watched_at = $movie->ticket_datetime;
        }
        $movie->ticket_price = $input['ticket_price'];
        $movie->ticket_row = $input['ticket_row'];
        $movie->ticket_seat = $input['ticket_seat'];
        $movie->plays = $movie->plays + 1;
        $destinationFile = $movie->slug.'.png';
        $trakt->addSyncHistory($movie->id_imdb, $movie->ticket_datetime->setTimezone('UTC')->toDateTimeString());

        if (env('APP_ENV', false) == 'local') {
            $destinationPath = public_path('img\\tickets\\'.$movie->year.'\\');
        } else {
            $destinationPath = public_path('img/tickets/'.$movie->year.'/');
        }
        if (! is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        file_put_contents($destinationPath.$destinationFile, file_get_contents(strip_tags($input['ticket_image'])));
        $test = getimagesize($destinationPath.$destinationFile);
        if ($test[0] > 10) {
            $movie->save();
        }

        return redirect()->action('Admin\MovieTicketsAddAdminController@index');
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
