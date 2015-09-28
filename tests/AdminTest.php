<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;

/**
 * Class FrontEndTest
 */
class AdminTest extends TestCase
{

    use WithoutMiddleware;

    /**
     *
     */
    public function testAdminDashboard()
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
    public function testAdminEmailDrops()
    {
        $this->visit('/admin/emaildrop')
            ->see('recipient');
        $this->visit('/admin/emaildrop')
            ->click('3')
            ->seePageIs('/admin/emaildrop/3');
    }
}
