<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Class Date
 * @package App
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
