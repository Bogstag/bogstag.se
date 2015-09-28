<?php

namespace App\Http\Controllers;

use App\Emaildrop;
use App\Emailstat;
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
        $emailstat = $this->getEmailStatChart();
        $emaildrops = $this->getEmailDropChart();

        return view('pages.ServerEmail', ['emailstatchart' => $emailstat, 'emaildrops' => $emaildrops]);
    }

    /**
     * @return mixed
     */
    public function getEmailDropChart()
    {
        return Emaildrop::select(array('subject', 'Spf', 'Spamscore', 'Spamflag', 'DkimCheck'))->
        where('public', '=', 1)->orderBy('created_at', 'desc')->limit(20)->get()->toarray();
    }

    /**
     * @return mixed
     */
    public function getEmailStatChart()
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
        $dateFormat = 'Y-m-d';
        $isStacked = true;

        return (new Chart\ColumnChartController)
            ->createColumnChart($name, $title, $dataTableColumns, $dataTableRows, $dateFormat, $isStacked);
    }
}
