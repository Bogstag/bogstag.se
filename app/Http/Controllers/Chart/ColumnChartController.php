<?php

namespace App\Http\Controllers\Chart;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Khill\Lavacharts\Lavacharts;

/**
 * Class ColumnChartController
 * @package App\Http\Controllers\Chart
 */
class ColumnChartController extends ChartController
{

    /**
     * @var
     */
    public $columnChart;
    /**
     * @var
     */
    public $createColumnChart;

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
    public function getColumnChart($name = '', $title = '')
    {
        $this->columnChart = \Lava::ColumnChart($name, $this->lavaDataTable);
        $this->setTitle($title);
    }

    /**
     * @param $name
     * @param $title
     * @param $dataTableColumns
     * @param Collection $dataTableRows
     * @return mixed
     */
    public function createColumnChart($name, $title, $dataTableColumns, Collection $dataTableRows)
    {

        $this->getLavaDataTable($dataTableColumns, $dataTableRows);
        $this->getColumnChart($name, $title);
        return $this->columnChart;
    }

    /**
     * @param $title
     */
    public function setTitle($title)
    {
        $this->columnChart->title($title);
    }
}
