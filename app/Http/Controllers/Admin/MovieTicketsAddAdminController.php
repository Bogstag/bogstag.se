<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Integration\TraktTv\TraktTv;
use Illuminate\Http\Request;
use Storage;

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
        $destinationFile = strip_tags($movie->slug.'.png');
        $trakt->addSyncHistory($movie->id_imdb, $movie->ticket_datetime->setTimezone('UTC')->toDateTimeString());

        $destinationPath = '/img/tickets/'.strip_tags($movie->year).'/';

        if (! Storage::disk('public')->exists($destinationPath)) {
            Storage::disk('public')->makeDirectory($destinationPath);
        }
        Storage::disk('public')->put(
            $destinationPath.$destinationFile,
            file_get_contents(strip_tags($input['ticket_image']))
        );
        $movie->save();

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
