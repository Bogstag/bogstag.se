<?php

namespace App\Http\Controllers\Admin;

use App\Emaildrop;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Mailgun\Mailgun;
use yajra\Datatables\Datatables;

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
        $title = "EmailDrops";

        return view('admin.dashboard.EmailDrops', ['title' => $title]);
    }

    public function getEmailDropsData()
    {
        $emailDrops = Emaildrop::select(array(
            'id',
            'created_at',
            'recipient',
            'sender',
            'subject',
        ))->
        orderby('id', 'desc')->limit(200)->get();

        return Datatables::of($emailDrops)
            ->editColumn('created_at', '{!! $created_at->diffForHumans() !!}')
            ->editColumn('id', '<a href="{{ URL::secure(\'/admin/emaildrop\', $id)}}">{{$id}}</a>')
            ->make(true);
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
        $title = "EmailDrop " . $id;
        $emailDrop = Emaildrop::find($id);

        return view('admin.dashboard.EmailDrop', ['title' => $title, 'emaildrop' => $emailDrop]);
    }

    public function setAdressToOkMailGun($recipient)
    {

        $mgClient = new Mailgun(env('Mailgun_Secret_API_Key', false));
        $defaultAddress = env('Mailgun_Forward_Address', false);
        $result = $mgClient->post("routes", array(
            'priority'    => 2000,
            'expression'  => 'match_recipient("'.$recipient.'")',
            'action'      => array('forward("'.$defaultAddress.'")', 'stop()'),
            'description' => 'Ok'
        ));
        return $result->http_response_code.": ".$result->http_response_body->message;
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
