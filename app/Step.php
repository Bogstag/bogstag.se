<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Class Step
 * @package App
 */
class Step extends Model
{

    /**
     * @var array
     */
    protected $dates = ['datetime'];

    /**
     * @var string
     */
    protected $primaryKey = 'step_id';

    /**
     * @var bool
     */
    public $incrementing = false;
    /**
     * Indicates what can be submitted to update.
     * @var array
     */
    protected $fillable = ['step_id'];

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function date()
    {
        return $this->belongsTo('App\Date');
    }

    /**
     * @param $step_id
     */
    public function setStepId($step_id)
    {
        $this->step_id = $step_id;
    }

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
     * @param $steps
     * @return array
     */
    public function transformStepCollection($steps)
    {
        return array_map([$this, 'transformStep'], $steps->toArray());
    }

    /**
     * @param $steps
     * @return array
     */
    public function transformStep($steps)
    {
        return array_values([
            'datetime' => $steps['datetime'],
            'steps'    => $steps['steps']
        ]);
    }

    public function transformDurationCollection($steps)
    {
        return array_map([$this, 'transformDuration'], $steps->toArray());
    }

    /**
     * @param $steps
     * @return array
     */
    public function transformDuration($steps)
    {
        return array_values([
            'datetime' => $steps['datetime'],
            'duration' => $steps['duration']
        ]);
    }

    public function transformPaceCollection($steps)
    {
        return array_map([$this, 'transformPace'], $steps->toArray());
    }

    /**
     * @param $steps
     * @return array
     */
    public function transformPace($steps)
    {
        return array_values([
            'datetime' => $steps['datetime'],
            'pace' => $steps['pace']
        ]);
    }
}
