<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Movie.
 */
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

    /**
     * @var array
     */
    protected $casts = ['genres' => 'array'];

    /**
     * @param $query
     */
    public function scopeWatchedMovies($query)
    {
        $query->select('id', 'title', 'slug', 'ticket_datetime', 'year');
    }

    /**
     * @return string
     */
    public function getTicketImageUrlAttribute()
    {
        return secure_url('img/tickets/'.$this->year.'/'.$this->slug.'.png');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images()
    {
        return $this->morphMany('App\Image', 'imageable');
    }

    /**
     * @return mixed
     */
    public function moviePosterPath()
    {
        return $this->morphMany('App\Image', 'imageable')->select('imagepath')->where('imagetype', 'poster');
    }

    /**
     * @return mixed
     */
    public function movieClearartPath()
    {
        return $this->morphMany('App\Image', 'imageable')->select('imagepath')->where('imagetype', 'clearart');
    }
}
