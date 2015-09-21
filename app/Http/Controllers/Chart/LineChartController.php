<?php

namespace App\Http\Controllers\Chart;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Khill\Lavacharts\Lavacharts;


class LineChartController extends ChartController
{

    public $lineChart;
    public $createLineChart;

    public function __construct()
    {
        parent::__construct();
        //$this->lineChart = $lineChart;
    }

    public function getLineChart($name = '', $title = '')
    {
        $this->lineChart = \Lava::LineChart($name);
        $this->setDataTable();
        $this->setTitle($title);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function createLineChart($name, $title, $dataTableColumns, $dataTableRows)
    {
        $this->getLavaDataTable($dataTableColumns, $dataTableRows);
        $this->getLineChart($name, $title);
        return $this->lineChart;
    }

    /**
     * @param $title
     */
    public function setTitle($title)
    {
        $this->lineChart->title($title);
    }

    public function setDataTable()
    {
        $this->lineChart->dataTable($this->lavaDataTable);
    }
}
