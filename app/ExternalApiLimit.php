<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\ExternalApiLimit.
 *
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $external_api_name
 * @property int $external_api_limit
 * @property \Carbon\Carbon $external_api_limit_interval
 * @property int $external_api_count
 * @property \Carbon\Carbon $limit_interval_start
 * @property \Carbon\Carbon $limit_interval_end
 * @property-read mixed $external_api_limit_left
 */
class ExternalApiLimit extends Model
{
    /**
     * @var string
     */
    protected $table = 'external_api_limits';

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var array
     */
    protected $fillable = ['external_api_name', 'external_api_limit_interval'];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'limit_interval_start',
        'limit_interval_end',
    ];

    /**
     * @return int
     */
    public function getExternalApiLimitLeftAttribute()
    {
        return (int) $this->external_api_limit - $this->external_api_count;
    }

    /**
     * @param $externalApiLimitInterval
     * @param Carbon $now
     *
     * @return array
     */
    public static function convertIntervalStringToStartEnd($externalApiLimitInterval, Carbon $now)
    {
        $Start = $now;
        $End = clone $now;

        if ($externalApiLimitInterval == 'Day') {
            $Start = $Start->startOfDay();
            $End = $End->endOfDay();
        }

        if ($externalApiLimitInterval == '5min') {
            $End->addMinutes(5);
        }

        return (array) ['limit_interval_start' => $Start, 'limit_interval_end' => $End];
    }
}
