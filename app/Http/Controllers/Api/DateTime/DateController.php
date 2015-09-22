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
     * @var Date
     */
    protected $date;

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->date = new Date();
        $limit = \Input::get('limit', 10);
        $dates = $this->date->limit($limit)->get()->toArray();

        return response()->json($dates);
    }

    /**
     * @param Request $request
     * @return Date|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
    }

    /**
     * @param array $dates
     */
    public function internalStoreRequest(array $dates)
    {
        array_map([$this, 'internalStore'], $dates);
    }

    /**
     * @param $date
     */
    public function internalStore(Carbon $date)
    {
        $this->date = new Date();
        if ($this->date->where('date', $date->format('Y-m-d'))->exists()) {
        } else {
            $this->generateDate($date);
            $this->date->save();
        }
    }

    /**
     * @param $date
     * @return mixed
     */
    public function generateDate(Carbon $date)
    {
        $this->date->date_id = $date->format('Ymd');
        $this->date->date = $date->format('Y-m-d');
        $this->date->year = $date->format('Y');
        $this->date->month = $date->format('m');
        $this->date->fullmonth = $date->format('F');
        $this->date->shortmonth = $date->format('M');
        $this->date->day = $date->format('d');
        $this->date->fullday = $date->format('l');
        $this->date->shortday = $date->format('D');
        $this->date->dayofweek = $date->format('N'); //ISO-8601 1=monday, 7=Sunday
        $this->date->dayofyear = $date->format('z');
        $this->date->week = $date->format('W');
        $this->date->nrdaysinmonth = $date->format('t');
        $this->date->leapyear = $date->format('L'); // 1=true, 0=false
    }
}
