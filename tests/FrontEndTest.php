<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class FrontEndTest
 */
class FrontEndTest extends TestCase
{

    /**
     *
     */
    public function testHomePage()
    {
        $this->visit('/')
            ->see('Home Page')
            ->click('Home')
            ->seePageIs('/');
    }

    public function testLoginPage()
    {
        $this->visit('/')
            ->click('Login')
            ->see('Login')
            ->seePageIs('/login');
    }

    /**
     *
     */
    public function testAboutPage()
    {
        $this->visit('/about')
            ->see('About')
            ->click('About')
            ->seePageIs('/about');
    }

    /**
     *
     */
    public function testStepsPage()
    {
        $this->visit('/')
            ->click('Steps')
            ->see('Activity / Steps')
            ->seePageIs('/activity/steps');
    }

    /**
     *
     */
    public function testEmailPage()
    {
        $this->visit('/')
            ->click('Email')
            ->see('Server / Email')
            ->seePageIs('/server/email');
    }

    public function testSteamPage()
    {
        $this->visit('/')
            ->click('Steam')
            ->see('Steam Games')
            ->seePageIs('/game/steam');
    }
}
