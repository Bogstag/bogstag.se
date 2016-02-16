<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class APIController.
 */
class APIController extends Controller
{
    /**
     * @param Request $request
     *
     * @return array|Request
     */
    public function removeApiKeyFromRequest(Request $request)
    {
        $request = $request->except(['apikey']);

        return $request;
    }

    /**
     * @param $date
     *
     * @return Carbon
     */
    public function setCarbonDate($date)
    {
        return new Carbon($date);
    }
}
