<?php

namespace App\Http\Controllers\Chart;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class BarChartController
 * @package App\Http\Controllers\Chart
 */
class BarChartController extends ChartController
{

    /**
     * @var
     */
    public $barChart;
    /**
     * @var
     */
    public $createBarChart;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string $name
     * @param string $title
     */
    public function getBarChart($name = '', $title = '')
    {
        $this->barChart = \Lava::BarChart($name, $this->lavaDataTable);
        $this->setTitle($title);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function createBarChart($name, $title, $dataTableColumns, Collection $dataTableRows)
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
}
