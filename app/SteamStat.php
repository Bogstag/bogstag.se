<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * A model for steam game stats.
 * 
 * Class SteamStat
 *
 * @package App
 * @property integer $id
 * @property integer $steam_games_id
 * @property string $name
 * @property integer $value
 * @property string $displayName
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\SteamGame $game
 * @property-read mixed $displayname
 */
class SteamStat extends Model
{

    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * The id is auto increment in the db.
     * @var bool
     */
    public $incrementing = true;

    /**
     * The database has a composite key, that is not supported in Eloquent.
     * So for this model i use id as primary.
     * Otherwise saves is not going to work.
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * This belongs to a steam game
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo('App\SteamGame');
    }

    /**
     * Steam is worthless when setting display names.
     * This check if display name is null.
     * If it is null then use name instead.
     * @param $value
     * @return mixed
     */
    public function getDisplaynameAttribute($value)
    {
        if ($value === null) {
            return $this->name;
        } else {
            return $value;
        }
    }
}
