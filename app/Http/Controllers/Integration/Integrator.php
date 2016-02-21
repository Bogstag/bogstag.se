<?php

namespace App\Http\Controllers\Integration;

use App\ExternalApiLimit;
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

    /**
     * @param Carbon $now
     * @param string $externalApiName
     * @param int    $externalApiLimit
     * @param string $externalApiLimitInterval
     *
     * @return mixed
     */
    public function addExternalAPILimitCounter(
        Carbon $now,
        $externalApiName = 'test',
        $externalApiLimit = 100000,
        $externalApiLimitInterval = 'Day'
    ) {
        $ExternalApiLimit = ExternalApiLimit::where('external_api_name', $externalApiName)
            ->where('external_api_limit_interval', $externalApiLimitInterval)
            ->where('limit_interval_start', '<=', $now->toDateTimeString())
            ->where('limit_interval_end', '>=', $now->toDateTimeString())
            ->first();

        if (empty($ExternalApiLimit)) {
            $ExternalApiLimit = new ExternalApiLimit();
            $ExternalApiLimit->external_api_name = $externalApiName;
            $ExternalApiLimit->external_api_limit_interval = $externalApiLimitInterval;
            $ExternalApiLimit->external_api_limit = $externalApiLimit;
            $StartEnd = ExternalApiLimit::convertIntervalStringToStartEnd(
                $externalApiLimitInterval,
                $now
            );
            $ExternalApiLimit->limit_interval_start = $StartEnd['limit_interval_start'];
            $ExternalApiLimit->limit_interval_end = $StartEnd['limit_interval_end'];
        }
        $ExternalApiLimit->external_api_count = $ExternalApiLimit->external_api_count + 1;
        $ExternalApiLimit->save();

        return $ExternalApiLimit->external_api_limit_left;
    }
}
