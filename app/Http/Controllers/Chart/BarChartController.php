<?php

namespace App\Http\Controllers\Chart;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Khill\Lavacharts\Lavacharts;


class BarChartController extends ChartController
{

    public $barChart;
    public $createBarChart;

    public function __construct()
    {
        parent::__construct();
        //$this->lineChart = $lineChart;
    }

    public function getBarChart($name = '', $title = '')
    {
        $this->barChart = \Lava::BarChart($name);
        $this->setDataTable();
        $this->setTitle($title);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function createBarChart($name, $title, $dataTableColumns, $dataTableRows)
    {
        $this->getLavaDataTable($dataTableColumns, $dataTableRows);
        $this->getBarChart($name, $title);
        return $this->barChart;
    }

    /**
     * @param $title
     */
    public function setTitle($title)
    {
        $this->barChart->title($title);
    }

    public function setDataTable()
    {
        $this->barChart->dataTable($this->lavaDataTable);
    }
}
