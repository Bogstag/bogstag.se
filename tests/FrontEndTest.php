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
            ->see('Home Page');
    }

    /**
     *
     */
    public function testAboutPage()
    {
        $this->visit('/about')
            ->see('About');
        $this->visit('/')
            ->click('About')
            ->seePageIs('/about');
    }

    /**
     *
     */
    public function testStepsPage()
    {
        $this->visit('/activity/steps')
            ->see('Steps per day');
        $this->visit('/')
            ->click('Steps')
            ->seePageIs('/activity/steps');
    }

    /**
     *
     */
    public function testEmailPage()
    {
        $this->visit('/server/email')
            ->see('Message Delivery');
        $this->visit('/')
            ->click('Email')
            ->seePageIs('/server/email');
    }

    /**
     *
     */
    public function testDatePage()
    {
        $this->visit('/api/v1/date')
            ->see('date_id');
        $this->visit('/')
            ->click('Date')
            ->seePageIs('/api/v1/date');
    }

    /**
     *
     */
    public function testEmailStatPage()
    {
        $this->visit('/api/v1/emailstat')
            ->see('event');
        $this->visit('/')
            ->click('Email Stat')
            ->seePageIs('/api/v1/emailstat');
    }

    /**
     *
     */
    public function testStepPage()
    {
        $this->visit('/api/v1/step')
            ->see('step_id');
        $this->visit('/')
            ->click('Step')
            ->seePageIs('/api/v1/step');
    }
}
