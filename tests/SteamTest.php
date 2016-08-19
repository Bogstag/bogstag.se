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
}
