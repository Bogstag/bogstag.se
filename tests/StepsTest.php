<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class StepsTest.
 */
class StepsTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function testStoreSteps()
    {
        $step = factory(App\Step::class)->create([
            'date' => '2016-01-01 00:00:02',
        ]);

        $this->seeInDatabase('steps', ['date' => '2016-01-01 00:00:02']);
    }

    /**
     * @covers \App\Http\Controllers\StepCharts::getStepCharts
     */
    public function testStepsPage()
    {
        $this->visit('/')
            ->click('Steps')
            ->see('Activity / Steps')
            ->seePageIs('/activity/steps');
    }
}
