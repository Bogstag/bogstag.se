<?php
namespace App\Http\Controllers\Integration;

use Carbon\Carbon;
use App\Http\Controllers\Controller;

/**
 * Class Integrator
 * @package App\Http\Controllers\Integration
 */
class Integrator extends Controller
{

    /**
     * @param $NanoSeconds
     * @return Carbon
     */
    public function convertNanosToDateTime($NanoSeconds)
    {
        return (new Carbon)->timestamp($NanoSeconds / 1000000000);
    }
}
