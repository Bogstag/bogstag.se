<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Chart;
use App\Step;
use DB;

/**
 * Class PageChartController
 * @package App\Http\Controllers
 */
class PageChartController extends Controller
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
            ->groupby('datetime')->orderby('datetime', 'desc')->take(100)->get();
        $lineChart = $this->getStepChart($dataTableRows);

        $dataTableRows = Step::select(DB::raw(
            'datetime, sum(duration)/60 as duration'
        ))
            ->groupby('datetime')->orderby('datetime', 'desc')->take(100)->get();
        $barChart = $this->getColumnChart($dataTableRows);

        $dataTableRows = Step::select(DB::raw(
            'datetime, sum(steps)/sum(duration) as pace'
        ))
            ->groupby('datetime')->orderby('datetime', 'desc')->take(100)->get();
        $paceChart = $this->getPaceChart($dataTableRows);

        return view('pages.graph', ['barchart' => $barChart, 'linechart' => $lineChart, 'pacecount' => $paceChart]);
    }

    /**
     * @param Collection $dataTableRows
     * @return \Illuminate\Http\Response
     */
    public function getColumnChart(Collection $dataTableRows)
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
        $lineChart = (new Chart\LineChartController)->createLineChart($name, $title, $dataTableColumns,
            $dataTableRowsStep);

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
        $lineChart = (new Chart\LineChartController)->createLineChart($name, $title, $dataTableColumns,
            $dataTableRowsPace);

        return $lineChart;
    }

}

