<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Chart;
use App\Step;
use DB;

class PageChartController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function getStepCharts()
    {
        $dataTableRows = Step::select(DB::raw(
            'datetime, sum(steps) as steps, sum(duration)/60 as duration, sum(steps)/sum(duration) as pace'
        ))
            ->groupby('date_id')->orderby('date_id', 'desc')->take(10)->get();

        $lineChart = $this->getStepChart($dataTableRows);
        $barChart = $this->getColumnChart($dataTableRows);
        $paceChart = $this->getPaceChart($dataTableRows);

        return view('pages.graph', ['barchart' => $barChart, 'linechart' => $lineChart, 'pacecount' => $paceChart]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function getColumnChart($dataTableRows)
    {
        $dataTableRows = (new Step)->transformDurationCollection($dataTableRows);
        $dataTableColumns = array(
            array('date', 'Date'),
            array('number', 'Duration')
        );
        $name = 'stepduration';
        $title = 'Steps duration per day in minutes';
        $barChart = (new Chart\ColumnChartController)
            ->createColumnChart($name, $title, $dataTableColumns, $dataTableRows);

        return $barChart;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function getStepChart($dataTableRows)
    {
        $dataTableRows = (new Step)->transformStepCollection($dataTableRows);
        $dataTableColumns = array(
            array('date', 'Date'),
            array('number', 'Steps')
        );
        $name = 'stepcount';
        $title = 'Steps per day';
        $lineChart = (new Chart\LineChartController)->createLineChart($name, $title, $dataTableColumns, $dataTableRows);

        return $lineChart;
    }

    public function getPaceChart($dataTableRows)
    {
        $dataTableRows = (new Step)->transformPaceCollection($dataTableRows);
        $dataTableColumns = array(
            array('date', 'Date'),
            array('number', 'Pace')
        );
        $name = 'pacecount';
        $title = 'Pace (Steps per second) per day';
        $lineChart = (new Chart\LineChartController)->createLineChart($name, $title, $dataTableColumns, $dataTableRows);

        return $lineChart;
    }

}

