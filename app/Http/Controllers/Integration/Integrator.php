<?php

namespace App\Http\Controllers\Integration;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

/**
 * Class Integrator.
 */
class Integrator extends Controller
{
    /**
     * @param $NanoSeconds
     *
     * @return Carbon
     */
    public function convertNanosToDateTime($NanoSeconds)
    {
        return (new Carbon())->timestamp($NanoSeconds / 1000000000);
    }
}
