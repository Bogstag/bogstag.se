<?php

namespace App\Http\Controllers;

use App\Step;
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
        $dataTableRows = Step::select(DB::raw(
            'date, steps'
        ))
            ->groupby('date')->orderby('date', 'desc')->take(90)->get();
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
                'durationchart' => $durationChart, 'stepchart' => $stepChart, 'pacecount' => $paceChart,
            ]
        );
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
        $lineChart = (new Chart\LineChartController())->
        createLineChart($name, $title, $dataTableColumns, $dataTableRows);

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
        $lineChart = (new Chart\LineChartController())->
        createLineChart($name, $title, $dataTableColumns, $dataTableRows);

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
