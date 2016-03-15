<?php

namespace App\Http\Controllers;

use App\Step;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PageChartController.
 */
class StepCharts extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStepCharts()
    {
        $stepToday = $this->getStepsTodayChart();

        $dataTableRows = Step::select(DB::raw(
            'date, steps'
        ))
            ->groupby('date')->orderby('date', 'desc')->skip(1)->take(90)->get();
        $stepChart = $this->getStepChart($dataTableRows);

        $dataTableRows = Step::select(DB::raw(
            'date, duration/60 as duration'
        ))
            ->groupby('date')->orderby('date', 'desc')->take(90)->get();
        $durationChart = $this->getDurationChart($dataTableRows);

        $dataTableRows = Step::select(DB::raw(
            'date, steps/duration as pace'
        ))
            ->groupby('date')->orderby('date', 'desc')->take(90)->get();
        $paceChart = $this->getPaceChart($dataTableRows);

        return view(
            'pages.ActivitySteps',
            [
                'durationchart' => $durationChart,
                'stepchart'     => $stepChart,
                'pacecount'     => $paceChart,
                'stepToday'     => $stepToday,
            ]
        );
    }

    public function getStepsTodayChart()
    {
        $TodaySteps = (new Step())->where('date', Carbon::now()->toDateString())->first();
        if ($TodaySteps === null) {
            $TodaySteps = new Step();
        }
        $reasons = \Lava::DataTable();

        $reasons->addStringColumn('Steps')
            ->addNumberColumn('Count')
            ->addRow(['Steps', $TodaySteps->steps])
            ->addRow(['Target', $TodaySteps->stepsLeftToTarget]);

        $chart = \Lava::DonutChart('stepToday', $reasons, [
            'title'  => 'Steps today',
            'legend' => [
                'position' => 'none',
            ],
        ]);

        return $chart;
    }

    /**
     * @param Collection $dataTableRows
     *
     * @return \Illuminate\Http\Response
     */
    public function getStepChart(Collection $dataTableRows)
    {
        $dataTableColumns = [
            ['date', 'Date'],
            ['number', 'Steps'],
        ];
        $name = 'stepcount';
        $title = 'Steps per day';
        $lineChart = (new Chart\LineChartController())
            ->createLineChart($name, $title, $dataTableColumns, $dataTableRows);

        return $lineChart;
    }

    /**
     * @param Collection $dataTableRows
     *
     * @return \Illuminate\Http\Response
     */
    public function getPaceChart(Collection $dataTableRows)
    {
        $dataTableColumns = [
            ['date', 'Date'],
            ['number', 'Pace'],
        ];

        $name = 'pacecount';
        $title = 'Pace (Steps per second) per day';
        $lineChart = (new Chart\LineChartController())
            ->createLineChart($name, $title, $dataTableColumns, $dataTableRows);

        return $lineChart;
    }

    /**
     * @param Collection $dataTableRows
     *
     * @return \Illuminate\Http\Response
     */
    public function getDurationChart(Collection $dataTableRows)
    {
        $dataTableColumns = [
            ['date', 'Date'],
            ['number', 'Duration'],
        ];
        $name = 'stepduration';
        $title = 'Steps duration per day in minutes';

        $barChart = (new Chart\ColumnChartController())
            ->createColumnChart($name, $title, $dataTableColumns, $dataTableRows);

        return $barChart;
    }
}
