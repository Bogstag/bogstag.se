<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Date
 *
 * @package App
 * @property integer $date_id
 * @property \Carbon\Carbon $date
 * @property integer $year
 * @property integer $month
 * @property string $fullmonth
 * @property string $shortmonth
 * @property integer $day
 * @property string $fullday
 * @property string $shortday
 * @property integer $dayofweek
 * @property integer $dayofyear
 * @property integer $week
 * @property integer $nrdaysinmonth
 * @property boolean $leapyear
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
     * @var bool
     */
    protected $fillable = array('date');
    /**
     * Indicates if the model should be timestamped.
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
