<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ChartController.
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
     * @param array      $dataTableColumns
     * @param Collection $dataTableRows
     */
    public function getLavaDataTable($dataTableColumns, Collection $dataTableRows, $dateFormat = 'Y-m-d H:i:s')
    {
        $this->lavaDataTable->setDateTimeFormat($dateFormat);
        $this->lavaDataTable->addColumns($dataTableColumns);
        $this->addRowsFromCollection($dataTableRows);
    }

    /**
     * Using the toArray() method of the collection, adds as rows to the datatable.
     * Columns must be added with the generic addColumn() method to define the model
     * property for the column id.
     * Stolen from kevinkhill/datatableplus.
     *
     * @param Collection $collection Collection of models
     *
     * @return self
     */
    public function addRowsFromCollection(Collection $collection)
    {
        $row = [];
        $colCount = $this->lavaDataTable->getColumnCount();
        foreach ($collection->toArray() as $collectionRow) {
            $row = [];
            for ($i = 0; $i < $colCount; $i++) {
                $row[] = $collectionRow[array_keys($collectionRow)[$i]];
            }
            $this->lavaDataTable->addRow($row);
        }

        return $row;
    }
}
