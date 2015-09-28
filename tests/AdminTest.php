<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class FrontEndTest
 */
class AdminTest extends TestCase
{

    use WithoutMiddleware;
    use DatabaseTransactions;
    /**
     *
     */
    public function testAdminDashboard()
    {
        /*
        $this->visit('admin/dashboard')
            ->see('Dashboard');
        $this->visit('admin/dashboard')
            ->click('Go to frontend')
            ->seePageIs('/');
        */
    }

    /**
     *
     */
    public function testAdminEmailDrops()
    {
        /*
        $this->visit('admin/emaildrop')
            ->see('recipient');
        $this->visit('admin/emaildrop')
            ->click('3')
            ->seePageIs('admin/emaildrop/3');
        */
    }
}
