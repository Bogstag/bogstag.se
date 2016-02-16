<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Date.
 *
 * @property int $date_id
 * @property \Carbon\Carbon $date
 * @property int $year
 * @property int $month
 * @property string $fullmonth
 * @property string $shortmonth
 * @property int $day
 * @property string $fullday
 * @property string $shortday
 * @property int $dayofweek
 * @property int $dayofyear
 * @property int $week
 * @property int $nrdaysinmonth
 * @property bool $leapyear
 * @property-read \App\Step $step
 */
class Date extends Model
{
    /**
     * @var array
     */
    protected $dates = ['date'];

    /**
     * @var string
     */
    protected $primaryKey = 'date_id';

    /**
     * @var bool
     */
    public $incrementing = false;
    /**
     * Indicates what can be submitted to update.
     *
     * @var bool
     */
    protected $fillable = ['date'];
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function step()
    {
        return $this->hasOne('App\Step');
    }
}
