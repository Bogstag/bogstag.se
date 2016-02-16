<?php

namespace App\Http\Controllers\Admin;

use App\Emaildrop;
use App\Http\Controllers\AdminController;

class DashboardController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $title = 'Dashboard';

        $emailDrop = Emaildrop::count();

        return view('admin.dashboard.index', compact('title', 'emailDrop'));
    }
}
