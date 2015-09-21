<?php

namespace App\Http\Controllers\Api\Activity;

use App\Step;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\APIController;
use App\Http\Requests;
use Carbon\Carbon;

class StepController extends APIController
{

    /**
     * @param Step $step
     */
    public function __construct(Step $step)
    {
        $this->step = $step;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $limit = \Input::get('limit', 10);
        $steps = $this->step->limit($limit)->get()->toArray();

        return response()->json($steps);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    public function internalStoreRequest(array $steps)
    {
        array_map([$this, 'internalStore'], $steps);
    }

    public function internalStore(array $steps)
    {
        $datestore = new Step;
        if ($this->step->where('step_id', $steps['date']->minute(0)->second(0)->format('YmdH'))->exists()) {
            var_dump('finns ' . $steps['date']->minute(0)->second(0)->format('YmdH'));
        } else {
            $datestore->step_id = $steps['date']->minute(0)->second(0)->format('YmdH');
            $datestore->date_id = $steps['date']->minute(0)->second(0)->format('Ymd');
            $datestore->datetime = $steps['date']->minute(0)->second(0)->timestamp;
            $datestore->duration = $steps['duration'];
            $datestore->steps = $steps['steps'];
            $datestore->save();
        }
    }
}
