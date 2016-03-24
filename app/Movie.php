<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
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
     * @var array
     */
    protected $dates = ['ticket_datetime', 'last_watched_at', 'created_at', 'updated_at', 'tmdb_updated_at'];
    /**
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * Indicates what can be submitted to update.
     *
     * @var array
     */
    protected $fillable = ['id_trakt'];

    protected $casts = ['genres' => 'array'];

}
