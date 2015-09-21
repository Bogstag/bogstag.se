<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Class APIController
 * @package App\Api\Controllers
 */
class APIController extends Controller
{

    /**
     * @param Request $request
     * @return array|Request
     */
    public function removeApikeyFromRequest(Request $request)
    {
        $request = $request->except(['apikey']);

        return $request;
    }

    /**
     * @param $date
     * @return Carbon
     */
    public function setCarbonDate($date)
    {
        return new Carbon($date);
    }
}
