<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for a game that i own.
 *
 * Class SteamGame
 *
 * @property int $id
 * @property string $name
 * @property int $playtime_forever
 * @property int $playtime_2weeks
 * @property bool $has_community_visible_stats
 * @property string $image_icon_url
 * @property string $image_logo_url
 * @property string $Image_background
 * @property string $image_header
 * @property bool $is_free
 * @property string $about_the_game
 * @property string $legal_notice
 * @property string $website
 * @property int $meta_critic_score
 * @property string $meta_critic_url
 * @property string $screenshot_path_thumbnail
 * @property string $screenshot_path_full
 * @property string $movie_thumbnail
 * @property string $movie_full_url
 * @property string $movie_name
 * @property \Carbon\Carbon $schema_updated_at
 * @property \Carbon\Carbon $player_stats_updated_at
 * @property \Carbon\Carbon $game_updated_at
 * @property \Carbon\Carbon $description_updated_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SteamGameAchievement[] $achievements
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SteamGameStat[] $stats
 *
 * @method static \Illuminate\Database\Query\Builder|\App\SteamGame listGames()
 * @method static \Illuminate\Database\Query\Builder|\App\SteamGame schemaNeedUpdate()
 * @method static \Illuminate\Database\Query\Builder|\App\SteamGame achievementsNeedUpdate()
 * @method static \Illuminate\Database\Query\Builder|\App\SteamGame descriptionNeedUpdate()
 */
class SteamGame extends Model
{
    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'schema_updated_at',
        'player_stats_updated_at',
        'game_updated_at',
        'description_updated_at',
    ];

    /**
     * @var array
     */
    public $fillable = ['id'];
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
        return $this->hasMany('App\SteamGameAchievement', 'steam_game_id');
    }

    /**
     * One game has many stats.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stats()
    {
        return $this->hasMany('App\SteamGameStat', 'steam_game_id');
    }

    /**
     * @var string
     */
    protected $table = 'steam_games';

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * Games i have played.
     *
     * @param $query
     */
    public function scopeListGames($query)
    {
        $query->where('playtime_forever', '>', 0)->orderBy('playtime_2weeks', 'desc')
            ->orderBy('playtime_forever', 'desc');
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
                $query->where('schema_updated_at', '<', Carbon::now()->subMonth()->toDateTimeString())
                    ->orWhere('schema_updated_at', null);
            })
            ->whereNotIn('id', $this->getGamesWithNoStats())
            ->orderBy('schema_updated_at', 'asc');
    }

    /**
     * @param $query
     */
    public function scopeDescriptionNeedUpdate($query)
    {
        $query->select('id')
            ->where(function (Builder $query) {
                $query->where('description_updated_at', '<', Carbon::now()->subMonth()->toDateTimeString())
                    ->orWhere('description_updated_at', null);
            })
            ->orderBy('description_updated_at', 'asc');
    }

    /**
     * Games that has no stats or achievements.
     * Games i have not played is worthless
     * Static Ids is games that steam say has stats, but don't have it.
     *
     * @return array
     */
    public static function getGamesWithNoStats()
    {
        $dynamicIds = self::select('id')->where('has_community_visible_stats', 0)
            ->orwhere('playtime_forever', 0)->pluck('id')->toArray();
        $staticIds = [32470, 219540, 243470, 322330, 223830, 298110, 33930, 301730, 251060];

        return (array) array_merge($dynamicIds, $staticIds);
    }

    /**
     * List Ids of all games that need to be updated.
     *
     * @param Builder $query
     */
    public function scopeAchievementsNeedUpdate(Builder $query)
    {
        $query->select('id')
            ->whereNotIn('id', $this->getGamesWithNoStats())
            ->where(function (Builder $query) {
                $query->where('player_stats_updated_at', '<', Carbon::now()->subMonth()->toDateTimeString())
                    ->where('playtime_2weeks', '>', 0)
                    ->orWhere('player_stats_updated_at', null);
            })
            ->orderBy('player_stats_updated_at', 'asc');
    }
}
