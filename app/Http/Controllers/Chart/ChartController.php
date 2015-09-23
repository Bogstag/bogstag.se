<?php

namespace App\Http\Controllers\Chart;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;

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
        $this->lavaDataTable->setDateTimeFormat('Y-m-d H:i:s');
    }

    /**
     * @param array $dataTableColumns
     * @param Collection $dataTableRows
     */
    public function getLavaDataTable($dataTableColumns, Collection $dataTableRows)
    {
        $this->lavaDataTable->addColumns($dataTableColumns);
        $this->addRowsFromCollection($dataTableRows);
    }

    /**
     * Using the toArray() method of the collection, adds as rows to the datatable.
     * Columns must be added with the generic addColumn() method to define the model
     * property for the column id.
     * Stolen from kevinkhill/datatableplus
     * @access public
     * @param  Collection $collection Collection of models
     * @return self
     */
    public function addRowsFromCollection(Collection $collection)
    {
        $colCount = $this->lavaDataTable->getColumnCount();
        foreach ($collection->toArray() as $collectionRow) {
            $row = [];
            for ($i = 0; $i < $colCount; $i ++) {
                $row[] = $collectionRow[array_keys($collectionRow)[$i]];
            }
            $this->lavaDataTable->addRow($row);
        }
        return $row;
    }
}
