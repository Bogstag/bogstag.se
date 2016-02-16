<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\SteamGameDescription.
 *
 * @property int $id
 * @property string $name
 * @property bool $is_free
 * @property string $about
 * @property string $header_image
 * @property string $legal_notice
 * @property int $meta_critic_score
 * @property string $meta_critic_url
 * @property string $website
 * @property string $detailed_description
 * @property string $screenshot_thumbnail
 * @property string $screenshot_full
 * @property string $movie_thumbnail
 * @property string $movie_full
 * @property string $background
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\SteamGame $game
 */
class SteamGameDescription extends Model
{
    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Ids are set from steam appid.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * This belongs to a steam game.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo('App\SteamGame');
    }
}
