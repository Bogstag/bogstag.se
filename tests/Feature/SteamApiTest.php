<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class FrontEndTest.
 */
class SteamApiTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    private $appid;

    private $name;

    private $playtime_2weeks;

    private $playtime_forever;

    private $img_icon_url;

    private $img_logo_url;

    private $header_image;

    private $website;

    private $screenshots_path_thumbnail;

    private $screenshots_path_full;

    private $movies_name;

    private $movies_thumbnail;

    private $movies_webm_max;

    private $background;

    private $stats_name;

    private $stats_displayName;

    private $achievements_name;

    private $achievements_displayName;

    private $achievements_description;

    private $achievements_icon;

    private $achievements_icongray;

    private $gameJson;

    private $steamGameDescriptionJson;

    private $steamSchemaJson;

    public function testArtisanCommand()
    {
        \Artisan::call(
            'steamapi:game',
            [
                'action' => 'update',
            ]
        );

        $this->assertTrue(true);
    }

    /**
     * @covers \App\Http\Controllers\SteamGameController
     * @covers \App\SteamGame
     */
    public function testParsingJsonAndStoringAGameAndStats()
    {
        $this->generateJson();
        $game = json_decode($this->gameJson);
        $SteamGame = \App\SteamGame::firstOrNew(['id' => $game->appid]);
        $SteamAPIGame = new \App\Http\Controllers\Integration\SteamAPI\SteamAPIGame();
        $SteamAPIGame->parseAndSaveSteamGame($SteamGame, $game);
        $this->assertDatabaseHas('steam_games', ['name' => $this->name]);

        $steamGameDescriptionJson = json_decode($this->steamGameDescriptionJson);
        $SteamAPIGameDescription = new \App\Http\Controllers\Integration\SteamAPI\SteamAPIGameDescription(
            new \App\SteamGame()
        );
        $SteamAPIGameDescription->updateSteamGameWithNewDescription($SteamGame, $steamGameDescriptionJson);
        $this->assertDatabaseHas(
            'steam_games',
            [
                'website'                   => $this->website,
                'screenshot_path_thumbnail' => $this->screenshots_path_thumbnail,
                'movie_full_url'            => $this->movies_webm_max,
            ]
        );

        $SteamGameSchemaFromAPI = json_decode($this->steamSchemaJson);
        $SteamAPIGameSchema = new \App\Http\Controllers\Integration\SteamAPI\SteamAPIGameSchema(new \App\SteamGame());
        $SteamAPIGameSchema->parseAndSaveAchievementSchema($SteamGame->id, $SteamGameSchemaFromAPI->achievements);
        $this->assertDatabaseHas(
            'steam_game_achievements',
            ['steam_game_id' => $this->appid, 'display_name' => $this->achievements_displayName]
        );

        $SteamAPIGameSchema->parseAndSaveStatSchema($SteamGame->id, $SteamGameSchemaFromAPI->stats);
        $this->assertDatabaseHas(
            'steam_game_stats',
            ['steam_game_id' => $this->appid, 'display_name' => $this->stats_displayName]
        );

        $SteamGames = \App\SteamGame::ListGames()->get();
        $this->assertEquals($this->name, $SteamGames[0]['attributes']['name']);

        $SteamGame = \App\SteamGame::find($game->appid)->stats->first();
        $this->assertEquals($this->stats_name, $SteamGame['attributes']['name']);

        $SteamGame = \App\SteamGame::find($game->appid)->achievements->first();
        $this->assertEquals($this->achievements_name, $SteamGame['attributes']['name']);
    }

    private function generateJson()
    {
        $this->appid = rand(100000, 999999);
        $this->name = uniqid();
        $this->playtime_2weeks = rand(100, 999);
        $this->playtime_forever = rand(1000, 9999);
        $this->img_icon_url = uniqid();
        $this->img_logo_url = uniqid();
        $this->header_image = uniqid();
        $this->website = uniqid();
        $this->screenshots_path_thumbnail = uniqid();
        $this->screenshots_path_full = uniqid();
        $this->movies_name = uniqid();
        $this->movies_thumbnail = uniqid();
        $this->movies_webm_max = uniqid();
        $this->background = uniqid();
        $this->stats_name = uniqid();
        $this->stats_displayName = uniqid();
        $this->achievements_name = uniqid();
        $this->achievements_displayName = uniqid();
        $this->achievements_description = uniqid();
        $this->achievements_icon = uniqid();
        $this->achievements_icongray = uniqid();

        $this->gameJson = '
        {  
           "appid":'.$this->appid.',
           "name":"'.$this->name.'",
           "playtime_2weeks":'.$this->playtime_2weeks.',
           "playtime_forever":'.$this->playtime_forever.',
           "img_icon_url":"'.$this->img_icon_url.'",
           "img_logo_url":"'.$this->img_logo_url.'",
           "has_community_visible_stats":true
        }';

        $this->steamGameDescriptionJson = '
        {  
           "type":"game",
           "name":"'.$this->name.'",
           "steam_appid":'.$this->appid.',
           "is_free":false,
           "header_image":"'.$this->header_image.'",
           "website":"'.$this->website.'",
           "screenshots":[  
              {  
                 "id":0,
                 "path_thumbnail":"'.$this->screenshots_path_thumbnail.'",
                 "path_full":"'.$this->screenshots_path_full.'"
              }
           ],
           "movies":[  
              {  
                 "name":"'.$this->movies_name.'",
                 "thumbnail":"'.$this->movies_thumbnail.'",
                 "webm":{  
                     "max":"'.$this->movies_webm_max.'"
                 }
              }
           ],
           "background":"'.$this->background.'"
        }';

        $this->steamSchemaJson = '
        {  
           "stats":[  
               {  
                 "name":"'.$this->stats_name.'",
                 "defaultvalue":0,
                 "displayName":"'.$this->stats_displayName.'"
              }
           ],
           "achievements":[  
              {  
                 "name":"'.$this->achievements_name.'",
                 "defaultvalue":0,
                 "displayName":"'.$this->achievements_displayName.'",
                 "hidden":0,
                 "description":"'.$this->achievements_description.'",
                 "icon":"'.$this->achievements_icon.'",
                 "icongray":"'.$this->achievements_icongray.'"
              }
           ]
        }';
    }
}
