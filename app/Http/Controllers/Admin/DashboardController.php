<?php

namespace App\Http\Controllers\Admin;

use App\Emaildrop;
use App\Http\Controllers\AdminController;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Admin
 */
class DashboardController extends AdminController
{
    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $title = 'Dashboard';

        $emailDrop = Emaildrop::count();

        return view('admin.dashboard.index', compact('title', 'emailDrop', 'externalApiLimits'));
    }
}
