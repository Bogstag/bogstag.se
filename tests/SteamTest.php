<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class FrontEndTest.
 */
class SteamTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * @covers \App\Http\Controllers\SteamGameController::index
     */
    public function testSteamPage()
    {
        $this->visit('/')
            ->click('Steam')
            ->see('Steam Games')
            ->seePageIs('/game/steam');
    }

    public function test_parsing_and_storing_a_game_and_stats()
    {
        $gamejson = '{"appid":280220,"name":"Creeper World 3: Arc Eternal","playtime_2weeks":4944,"playtime_forever":9719,"img_icon_url":"32c20617180519227d3a52f239db5f73158da45c","img_logo_url":"b6b5df5d1ac73f39c46de9d325b6b331906fe22c","has_community_visible_stats":true}';
        $game = json_decode($gamejson);
        $SteamGame = App\SteamGame::firstOrNew(['id' => $game->appid]);
        $SteamAPIGame = new App\Http\Controllers\Integration\SteamAPI\SteamAPIGame();
        $SteamAPIGame->parseAndSaveSteamGame($SteamGame, $game);
        $this->seeInDatabase('steam_games', ['name' => 'Creeper World 3: Arc Eternal']);
        $SteamAPIGame->runDescriptionSchemaAchievements($SteamGame);
        $this->seeInDatabase('steam_games', ['website' => 'http://knucklecracker.com']);
        $this->seeInDatabase('steam_game_achievements', ['steam_game_id' => '280220', 'display_name' => 'Training Wheels']);
        $this->seeInDatabase('steam_game_stats', ['steam_game_id' => '280220', 'display_name' => 'Inhibitors destroyed in Tormented Space']);
    }
}
