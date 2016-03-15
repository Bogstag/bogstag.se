<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Step.
 *
 * @property int $step_id
 * @property int $date_id
 * @property int $steps
 * @property int $duration
 * @property \Carbon\Carbon $datetime
 * @property-read \App\Date $date
 */
class Step extends Model
{
    /**
     * @var array
     */
    protected $dates = ['date', 'created_at', 'updated_at'];

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var bool
     */
    public $incrementing = true;
    /**
     * Indicates what can be submitted to update.
     *
     * @var array
     */
    protected $fillable = ['date'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    public $targetStep = 10000;

    /**
     * @param $date
     */
    public function setDate($date)
    {
        $date = new Carbon($date);
        $this->date_id = $date->minute(0)->second(0)->format('Ymd');
    }

    /**
     * @param $date
     */
    public function setDatetime($date)
    {
        $date = new Carbon($date);
        $date->minute(0)->second(0)->timestamp;
        $this->datetime = $date;
    }

    public function getStepsLeftToTargetAttribute($value)
    {
        if ($this->steps > $this->targetStep) {
            return 0;
        }
        return $this->targetStep - $this->steps;
    }
}
