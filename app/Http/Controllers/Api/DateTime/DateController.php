<?php

namespace App\Http\Controllers\Api\DateTime;

use App\Date;
use App\Http\Controllers\Api\APIController;
use Illuminate\Http\Request;
use Illuminate\Http;
use Carbon\Carbon;

/**
 * Class DateController
 * Controller the dates, whip them into submission.
 * @package App\Http\Controllers\Api\DateTime
 */
class DateController extends APIController
{

    /**
     * DateController constructor.
     * @param Date $date
     */
    public function __construct()
    {

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $limit = \Input::get('limit', 10);
        $dates = (new Date)->limit($limit)->get()->toArray();

        return response()->json($dates);
    }

    /**
     * @param Request $request
     * @return Date|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
    }

    public function internalStoreRequest(array $dates)
    {
        array_map([$this, 'internalStore'], $dates);
    }

    public function internalStore(array $dates)
    {
        $datestore = new Date;
        if ($datestore->where('date', $dates['date']->format('Y-m-d'))->exists()) {
        } else {
            $datestore = new Date;
            $datestore->date_id = $dates['date']->format('Ymd');
            $datestore->date = $dates['date']->format('Y-m-d');
            $datestore->year = $dates['date']->format('Y');
            $datestore->month = $dates['date']->format('m');
            $datestore->fullmonth = $dates['date']->format('F');
            $datestore->shortmonth = $dates['date']->format('M');
            $datestore->day = $dates['date']->format('d');
            $datestore->fullday = $dates['date']->format('l');
            $datestore->shortday = $dates['date']->format('D');
            $datestore->dayofweek = $dates['date']->format('N'); //ISO-8601 1=monday, 7=Sunday
            $datestore->dayofyear = $dates['date']->format('z');
            $datestore->week = $dates['date']->format('W');
            $datestore->nrdaysinmonth = $dates['date']->format('t');
            $datestore->leapyear = $dates['date']->format('L'); // 1=true, 0=false
            $datestore->save();
        }
    }
}
