<?php

namespace App\Http\Controllers\Api\Activity;

use App\Step;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\APIController;
use App\Http\Requests;
use Carbon\Carbon;

/**
 * Class StepController
 * @package App\Http\Controllers\Api\Activity
 */
class StepController extends APIController
{

    /**
     * @var Step
     */
    protected $step;

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->step = new Step();
        $limit = \Input::get('limit', 10);
        $steps = $this->step->limit($limit)->get()->toArray();

        return response()->json($steps);
    }

    /**
     * @param array $steps
     */
    public function internalStoreRequest(array $steps)
    {
        array_map([$this, 'internalStore'], $steps);
    }

    /**
     * @param array $step
     */
    public function internalStore(array $step)
    {
        $this->step = new Step;
        if ($this->step->where('step_id', $step['date']->minute(0)->second(0)->format('YmdH'))->exists()) {
        } else {
            $this->setStep($step);
            $this->step->save();
        }
    }

    /**
     * @param array $step
     */
    public function setStep(array $step)
    {
        $this->step->step_id = $step['date']->minute(0)->second(0)->format('YmdH');
        $this->step->date_id = $step['date']->minute(0)->second(0)->format('Ymd');
        $this->step->datetime = $step['date']->minute(0)->second(0)->timestamp;
        $this->step->duration = $step['duration'];
        $this->step->steps = $step['steps'];
    }
}
