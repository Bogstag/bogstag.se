<?php

namespace App\Http\Controllers\Api\Activity;

use App\Http\Controllers\Api\APIController;
use App\Step;
use Illuminate\Http\Request;

/**
 * Class StepController.
 */
class StepController extends APIController
{
    /**
     * @var Step
     */
    protected $step;

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->step = new Step();
        $limit = $request->input('limit', 10);
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
        $this->step = new Step();
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
