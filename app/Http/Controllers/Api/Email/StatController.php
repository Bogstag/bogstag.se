<?php

namespace App\Http\Controllers\Api\Email;

use App\Http\Controllers\Api\APIController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Emailstat;

class StatController extends APIController
{

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $emailstat = new Emailstat();
        $timestamp = new Carbon();
        $date = $timestamp->createFromTimeStamp($request->input('timestamp'))->toDateString();
        $domain = $request->input('domain');
        $event = $request->input('event');

        if ($emailstat->
        where('date', '=', $date)->
        where('domain', '=', $domain)->
        where('event', '=', $event)->exists()
        ) {
            $emailstat = $emailstat->
            where('date', '=', $date)->
            where('domain', '=', $domain)->
            where('event', '=', $event)->first();
            $emailstat->count += 1;
            $emailstat->save();
        } else {
            $emailstat->event = $event;
            $emailstat->domain = $domain;
            $emailstat->count = 1;
            $emailstat->date = $date;
            $emailstat->save();
        }
    }

    /**
     * Display the specified resource.
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
