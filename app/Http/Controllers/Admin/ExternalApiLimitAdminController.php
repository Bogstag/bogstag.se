<?php

namespace App\Http\Controllers\Admin;

use App\ExternalApiLimit;
use App\Http\Controllers\AdminController;
use App\Http\Requests;
use Carbon\Carbon;

class ExternalApiLimitAdminController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'External Api Limits';

        $externalApiLimits = ExternalApiLimit::where('limit_interval_start', '>', Carbon::now()->subDays(31))
            ->orderBy('limit_interval_start', 'desc')
            ->get();

        return view('admin.dashboard.ExternalApiLimit.ExternalApiLimitList', compact('title', 'externalApiLimits'));
    }
}
