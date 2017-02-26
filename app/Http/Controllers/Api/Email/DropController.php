<?php

namespace App\Http\Controllers\Api\Email;

use App\Emaildrop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DropController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $emailstat = new Emaildrop();
        $emailstat->sender = $request->input('sender');
        $emailstat->subject = $request->input('subject');
        $emailstat->Spf = $request->input('X-Mailgun-Spf');
        $emailstat->Spamscore = $request->input('X-Mailgun-Sscore');
        $emailstat->Spamflag = $request->input('X-Mailgun-Sflag');
        $emailstat->DkimCheck = $request->input('X-Mailgun-Dkim-Check-Result');
        $emailstat->public = $request->input('public');
        $emailstat->recipient = $request->input('recipient');
        $emailstat->bodyplain = $request->input('body-plain');
        $emailstat->messageheaders = $request->input('message-headers');
        $emailstat->save();
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
        //
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
