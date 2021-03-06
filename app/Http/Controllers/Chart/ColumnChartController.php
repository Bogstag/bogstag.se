<?php

namespace App\Http\Controllers\Chart;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class ColumnChartController.
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

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string     $name
     * @param string     $title
     * @param bool|false $isStacked
     */
    public function getColumnChart($name = '', $title = '', $isStacked = false)
    {
        $this->columnChart = \Lava::ColumnChart($name, $this->lavaDataTable, ['isStacked' => $isStacked]);
        $this->setTitle($title);
    }

    /**
     * @param $name
     * @param $title
     * @param $dataTableColumns
     * @param Collection $dataTableRows
     * @param string     $dateFormat
     * @param bool|false $isStacked
     *
     * @return mixed
     */
    public function createColumnChart(
        $name,
        $title,
        $dataTableColumns,
        Collection $dataTableRows,
        $dateFormat = 'Y-m-d H:i:s',
        $isStacked = false
    ) {
        $this->getLavaDataTable($dataTableColumns, $dataTableRows, $dateFormat);
        $this->getColumnChart($name, $title, $isStacked);

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
