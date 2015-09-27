<?php

namespace App\Http\Controllers;

use App\Emailstat;
use App\Http\Requests;
use DB;
use App\Http\Controllers\Chart;

/**
 * Class EmailCharts
 * @package App\Http\Controllers
 */
class EmailCharts extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function getEmailCharts()
    {
        $dataTableRows = Emailstat::select(DB::raw(
            'date,
            SUM(CASE WHEN event="bounced" THEN count END) as bounced,
            SUM(CASE WHEN event="complained" THEN count END) as complained,
            SUM(CASE WHEN event="dropped" THEN count END) as dropped,
            SUM(CASE WHEN event="delivered" THEN count END) as delivered'
        ))
            ->groupby('date')->orderby('date', 'desc')->take(100)->get();

        $dataTableColumns = array(
            array('date', 'Date'),
            array('number', 'Bounced'),
            array('number', 'Complained'),
            array('number', 'Dropped'),
            array('number', 'Delivered')
        );
        $name = 'emailstat';
        $title = 'Message Delivery';
        $emailstat = (new Chart\ColumnChartController)
            ->createColumnChart($name, $title, $dataTableColumns, $dataTableRows, $dateFormat = 'Y-m-d', $isStacked = true);
        return view('pages.ServerEmail', ['emailstatchart' => $emailstat]);
    }
}
