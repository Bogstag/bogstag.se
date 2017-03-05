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

    /**
     * @group externalApi
     */
    public function testArtisanCommand()
    {
        \Artisan::call(
            'steamapi:game',
            [
                'action' => 'update',
                '--rpg' => true,
            ]
        );

        $this->assertTrue(true);
    }
}
