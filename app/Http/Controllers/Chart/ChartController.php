<?php

namespace App\Http\Controllers\Chart;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Khill\Lavacharts\Lavacharts;

class ChartController extends Controller
{

    public $lavaDataTable;

    /**
     * ChartController constructor.
     */
    public function __construct()
    {
        $this->lavaDataTable = \Lava::DataTable();
    }

    public function getLavaDataTable($dataTableColumns = array(), $dataTableRows = array())
    {

        $this->lavaDataTable->addColumns($dataTableColumns);
        $this->lavaDataTable->addRows($dataTableRows);
    }
}
