<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;

/**
 * Class FrontEndTest
 */
class FrontEndTest extends TestCase
{

    use WithoutMiddleware;

    /**
     *
     */
    public function testDashboard()
    {
        $this->visit('/admin/dashboard')
            ->see('Bogstag Admin');
        $this->visit('/admin/dashboard')
            ->click('Go to frontend')
            ->seePageIs('/');
    }

    /**
     *
     */
    public function testEmailDrops()
    {
        $this->visit('/admin/emaildrop')
            ->see('recipient');
        $this->visit('/admin/emaildrop')
            ->click('3')
            ->seePageIs('/admin/emaildrop/3');
    }
}
