<?php

namespace App\Http\Controllers\Admin;

//use Illuminate\Http\Request;
//use App\Http\Requests;
use App\Http\Controllers\AdminController;

class ProfileController extends AdminController
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
        return view('admin.dashboard.Profile', ['title' => 'Profile']);
    }
}
