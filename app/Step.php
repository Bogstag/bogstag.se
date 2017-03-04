<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Step.
 *
 * @property int            $step_id
 * @property int            $date_id
 * @property int            $steps
 * @property int            $duration
 * @property \Carbon\Carbon $datetime
 * @property-read \App\Date $date
 * @method limit(integer $limit)
 */
class Step extends Model
{
    /**
     * @var bool
     */
    public $incrementing = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var int
     */
    public $targetStep = 10000;

    /**
     * @var array
     */
    protected $dates = ['date', 'created_at', 'updated_at'];

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates what can be submitted to update.
     *
     * @var array
     */
    protected $fillable = ['date'];

    /**
     * This is done because my prod server returns this as string not int.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'steps' => 'integer', 'duration' => 'integer'];

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

    /**
     * @return int
     */
    public function getStepsLeftToTargetAttribute()
    {
        if ($this->steps > $this->targetStep) {
            return 0;
        }

        return (int) $this->targetStep - $this->steps;
    }
}
