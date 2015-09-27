<?php

namespace App\Http\Controllers\Chart;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class LineChartController
 * @package App\Http\Controllers\Chart
 */
class LineChartController extends ChartController
{

    /**
     * @var
     */
    public $lineChart;
    /**
     * @var
     */
    public $createLineChart;

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
    public function getLineChart($name = '', $title = '')
    {

        $this->lineChart = \Lava::LineChart($name, $this->lavaDataTable);
        $this->setTitle($title);
    }

    /**
     * @param $name
     * @param $title
     * @param $dataTableColumns
     * @param Collection $dataTableRows
     * @return mixed
     */
    public function createLineChart($name, $title, $dataTableColumns, Collection $dataTableRows)
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
}
