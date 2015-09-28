<?php

namespace App\Http\Controllers\Admin;

use App\Emaildrop;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

class EmailDropController extends AdminController
{

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "EmailDrop";
        $emailDrops = Emaildrop::select(array(
            'id',
            'recipient',
            'sender',
            'subject',
            'Spamscore',
            'Spamflag',
        ))->
        orderBy('created_at', 'desc')->limit(200)->get()->toarray();

        return view('admin.dashboard.EmailDrops', ['title' => $title, 'emaildrops' => $emailDrops]);
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
        //
    }

    /**
     * Display the specified resource.
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = "EmailDrop ".$id;
        $emailDrop = Emaildrop::find($id);
        return view('admin.dashboard.EmailDrop', ['title' => $title, 'emaildrop' => $emailDrop]);
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
