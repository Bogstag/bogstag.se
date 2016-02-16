<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for a game that i own.
 *
 * Class SteamGame
 *
 * @property int $id
 * @property string $name
 * @property int $playtimeforever
 * @property int $playtime2weeks
 * @property string $iconurl
 * @property string $logourl
 * @property bool $hasstats
 * @property string $schema_updated_at
 * @property string $player_stats_updated_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SteamAchievement[] $achievements
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SteamStat[] $stats
 * @property-read \App\SteamGameDescription $descriptions
 *
 * @method static \Illuminate\Database\Query\Builder|\App\SteamGame listGames()
 * @method static \Illuminate\Database\Query\Builder|\App\SteamGame schemaNeedUpdate()
 * @method static \Illuminate\Database\Query\Builder|\App\SteamGame achievementsNeedUpdate()
 */
class SteamGame extends Model
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
     * One game has many achievements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function achievements()
    {
        return $this->hasMany('App\SteamAchievement', 'steam_games_id');
    }

    /**
     * One game has many stats.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stats()
    {
        return $this->hasMany('App\SteamStat', 'steam_games_id');
    }

    /**
     * One game has many stats.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function descriptions()
    {
        return $this->hasOne('App\SteamGameDescription', 'id');
    }

    /**
     * Games i have played.
     *
     * @param $query
     */
    public function scopeListGames($query)
    {
        $query->where('playtimeforever', '>', 0)->orderBy('playtime2weeks', 'desc')
            ->orderBy('playtimeforever', 'desc');
    }

    /**
     * Games that are not updated today.
     * Games that are not updated at all.
     * Games that are have stats.
     * These games need an update.
     *
     * @param $query
     */
    public function scopeSchemaNeedUpdate($query)
    {
        $query->select('id')
            ->where(function (Builder $query) {
                $query->where('schema_updated_at', '<', date('Y-m-d'))
                    ->orWhere('schema_updated_at', null);
            })
            ->whereNotIn('id', $this->getGamesWithNoStats())
            ->orderBy('schema_updated_at', 'asc');
    }

    /**
     * Games that has no stats or achievements.
     * Games i have not played is worthless
     * Static Ids is games that steam say has stats, but don't have it.
     *
     * @return array
     */
    public function getGamesWithNoStats()
    {
        $dynamicIds = $this->select('id')->where('hasstats', false)
            ->orwhere('playtimeforever', 0)->lists('id')->toArray();
        $staticIds = [243470, 322330, 223830, 298110, 33930, 301730, 251060];

        return array_merge($dynamicIds, $staticIds);
    }

    /**
     * List Ids of all games that need to be updated.
     *
     * @param $query
     */
    public function scopeAchievementsNeedUpdate(Builder $query)
    {
        $query->select('id')
            ->whereNotIn('id', $this->getGamesWithNoStats())
            ->where(function (Builder $query) {
                $query->where('player_stats_updated_at', '<', date('Y-m-d'))
                    ->where('playtime2weeks', '>', 0)
                    ->orWhere('player_stats_updated_at', null);
            })
            ->orderBy('player_stats_updated_at', 'asc');
    }
}
