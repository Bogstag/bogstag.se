<?php


/**
 * Class FrontEndTest.
 */
class FrontEndTest extends TestCase
{
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

    public function testAboutPage()
    {
        $this->visit('/about')
            ->see('About')
            ->click('About')
            ->seePageIs('/about');
    }
}
