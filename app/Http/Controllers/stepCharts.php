<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\Chart;
use App\Step;
use DB;

/**
 * Class PageChartController
 * @package App\Http\Controllers
 */
class StepCharts extends Controller
{

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function getStepCharts()
    {
        $dataTableRows = Step::select(DB::raw(
            'datetime, sum(steps) as steps'
        ))
            ->groupby('date_id')->orderby('datetime', 'desc')->take(90)->get();
        $stepChart = $this->getStepChart($dataTableRows);

        $dataTableRows = Step::select(DB::raw(
            'datetime, sum(duration)/60 as duration'
        ))
            ->groupby('date_id')->orderby('datetime', 'desc')->take(90)->get();
        $durationChart = $this->getDurationChart($dataTableRows);

        $dataTableRows = Step::select(DB::raw(
            'datetime, sum(steps)/sum(duration) as pace'
        ))
            ->groupby('date_id')->orderby('datetime', 'desc')->take(90)->get();
        $paceChart = $this->getPaceChart($dataTableRows);

        return view('pages.ActivitySteps', ['durationchart' => $durationChart, 'stepchart' => $stepChart, 'pacecount' => $paceChart]);
    }

    /**
     * @param Collection $dataTableRows
     * @return \Illuminate\Http\Response
     */
    public function getDurationChart(Collection $dataTableRows)
    {
        $dataTableRowsDuration = (new Step)->transformDurationCollection($dataTableRows);
        $dataTableColumns = array(
            array('datetime', 'Date'),
            array('number', 'Duration')
        );
        $name = 'stepduration';
        $title = 'Steps duration per day in minutes';
        $barChart = (new Chart\ColumnChartController)
            ->createColumnChart($name, $title, $dataTableColumns, $dataTableRowsDuration);

        return $barChart;
    }

    /**
     * @param Collection $dataTableRows
     * @return \Illuminate\Http\Response
     */
    public function getStepChart(Collection $dataTableRows)
    {
        $dataTableRowsStep = (new Step)->transformStepCollection($dataTableRows);
        $dataTableColumns = array(
            array('datetime', 'Date'),
            array('number', 'Steps')
        );
        $name = 'stepcount';
        $title = 'Steps per day';
        $lineChart = (new Chart\LineChartController)->
        createLineChart($name, $title, $dataTableColumns, $dataTableRowsStep);

        return $lineChart;
    }

    /**
     * @param Collection $dataTableRows
     * @return \Illuminate\Http\Response
     */
    public function getPaceChart(Collection $dataTableRows)
    {
        $dataTableRowsPace = (new Step)->transformPaceCollection($dataTableRows);
        $dataTableColumns = array(
            array('datetime', 'Date'),
            array('number', 'Pace')
        );

        $name = 'pacecount';
        $title = 'Pace (Steps per second) per day';
        $lineChart = (new Chart\LineChartController)->
        createLineChart($name, $title, $dataTableColumns, $dataTableRowsPace);

        return $lineChart;
    }

}

