<?php

namespace App\Http\Controllers;

use App\Emaildrop;
use App\Emailstat;
use DB;

/**
 * Class EmailCharts.
 */
class emailCharts extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function getEmailCharts()
    {
        $emaildel = $this->getEmailDeliveryChart();
        $EmailDropsChart = $this->getEmailDropsChart();
        $EmailPublicDropList = $this->getEmailPublicDropList();

        return view('pages.ServerEmail', ['emaildel' => $emaildel, 'EmailPublicDropList' => $EmailPublicDropList, 'EmailDropsChart' => $EmailDropsChart]);
    }

    /**
     * @return mixed
     */
    public function getEmailPublicDropList()
    {
        return Emaildrop::select(['subject', 'Spf', 'Spamscore', 'Spamflag', 'DkimCheck'])->
        orderBy('created_at', 'desc')->limit(10)->get()->toarray();
    }

    /**
     * @return mixed
     */
    public function getEmailDropsChart()
    {
        $dataTableRows = Emaildrop::select(DB::raw(
            "DATE_FORMAT(created_at, '%Y-%m-%d') as Date, COUNT('id') AS Count"
        ))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->orderby('Date', 'asc')->take(100)->get();
        $dataTableColumns = [
            ['date', 'Date'],
            ['number', 'Count'],
        ];
        $name = 'emaildrops';
        $title = 'Droped by custom rule';
        $dateFormat = 'Y-m-d';

        return (new Chart\LineChartController())
            ->createLineChart($name, $title, $dataTableColumns, $dataTableRows, $dateFormat);
    }

    /**
     * @return mixed
     */
    public function getEmailDeliveryChart()
    {
        $dataTableRows = Emailstat::select(DB::raw(
            'date,
            SUM(CASE WHEN event="bounced" THEN count END) as bounced,
            SUM(CASE WHEN event="complained" THEN count END) as complained,
            SUM(CASE WHEN event="dropped" THEN count END) as dropped,
            SUM(CASE WHEN event="delivered" THEN count END) as delivered'
        ))
            ->groupby('date')->orderby('date', 'desc')->take(100)->get();

        $dataTableColumns = [
            ['date', 'Date'],
            ['number', 'Bounced'],
            ['number', 'Complained'],
            ['number', 'Dropped'],
            ['number', 'Delivered'],
        ];
        $name = 'emaildel';
        $title = 'Message Delivery';
        $dateFormat = 'Y-m-d';
        $isStacked = true;

        return (new Chart\ColumnChartController())
            ->createColumnChart($name, $title, $dataTableColumns, $dataTableRows, $dateFormat, $isStacked);
    }
}
