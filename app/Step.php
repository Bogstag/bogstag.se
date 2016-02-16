<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
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
     *
     * @var array
     */
    protected $fillable = ['step_id'];

    /**
     * Indicates if the model should be timestamped.
     *
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
     *
     * @return array
     */
    public function transformStepCollection(Collection $steps)
    {
        foreach ($steps as $step) {
            unset($step->{'duration'});
            unset($step->{'pace'});
        }

        return $steps;
    }

    /**
     * @param Collection $steps2
     *
     * @return Collection
     */
    public function transformDurationCollection(Collection $steps2)
    {
        foreach ($steps2 as $step2) {
            unset($step2->{'steps'});
            unset($step2->{'pace'});
        }

        return $steps2;
    }

    /**
     * @param Collection $steps3
     *
     * @return Collection
     */
    public function transformPaceCollection(Collection $steps3)
    {
        foreach ($steps3 as $step3) {
            unset($step3->{'steps'});
            unset($step3->{'duration'});
        }

        return $steps3;
    }
}
