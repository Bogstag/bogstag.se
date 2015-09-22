<?php

namespace App\Http\Controllers\Chart;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * Class ChartController
 * @package App\Http\Controllers\Chart
 */
class ChartController extends Controller
{

    /**
     * @var
     */
    public $lavaDataTable;

    /**
     * ChartController constructor.
     */
    public function __construct()
    {
        $this->lavaDataTable = \Lava::DataTable();
    }

    /**
     * @param array $dataTableColumns
     * @param array $dataTableRows
     */
    public function getLavaDataTable($dataTableColumns = array(), $dataTableRows = array())
    {

        $this->lavaDataTable->addColumns($dataTableColumns);
        $this->lavaDataTable->addRows($dataTableRows);
    }
}
