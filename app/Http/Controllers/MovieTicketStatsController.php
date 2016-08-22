<?php

namespace App\Http\Controllers;

use App\Movie;
use DB;
use Illuminate\Database\Eloquent\Collection;

class MovieTicketStatsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $TicketsPerYear = Movie::select(
            DB::raw(
                'DATE_FORMAT(ticket_datetime, \'%Y-01-01 00:00:00\') AS Year, SUM(ticket_price) as TotalCost, AVG(ticket_price) as AverageCostPerTicketInclFree, AVG(CASE WHEN ticket_price > 0 THEN ticket_price ELSE NULL END) AS AverageCostPerTicketExclFree, COUNT(ticket_datetime) as TotalTickets, COUNT(CASE WHEN ticket_price = 0 THEN 1 ELSE NULL END) AS NumberOfFreeTickets, COUNT(CASE WHEN ticket_price = 0 THEN NULL ELSE 1 END) AS NumberOfNotFreeTickets'
            )
        )
            ->whereNotNull('ticket_datetime')
            ->groupby(DB::raw('YEAR(ticket_datetime)'))
            ->orderby(DB::raw('YEAR(ticket_datetime)'))
            ->get();

        $TicketsTotal = Movie::select(
            DB::raw(
                'SUM(ticket_price) as TotalCost, AVG(ticket_price) as AverageCostPerTicketInclFree, AVG(CASE WHEN ticket_price > 0 THEN ticket_price ELSE NULL END) AS AverageCostPerTicketExclFree, COUNT(ticket_datetime) as TotalTickets, COUNT(CASE WHEN ticket_price = 0 THEN 1 ELSE NULL END) AS NumberOfFreeTickets, COUNT(CASE WHEN ticket_price = 0 THEN NULL ELSE 1 END) AS NumberOfNotFreeTickets'
            )
        )
            ->whereNotNull('ticket_datetime')
            ->get();

        $LineChartAverageTicketPricePerYear = $this->getLineChartAverageTicketPricePerYear(
            $TicketsPerYear->map(
                function (Movie $item) {
                    return collect(
                        [
                            'Year' => $item->Year,
                            'AverageCostPerTicketInclFree' => $item->AverageCostPerTicketInclFree,
                            'AverageCostPerTicketExclFree' => $item->AverageCostPerTicketExclFree,
                        ]
                    );
                }
            )
        );

        $BarChartNumberOfTicketsPerYear = $this->getBarChartNumberOfTicketsPerYear(
            $TicketsPerYear->map(
                function (Movie $item) {
                    if ($item->NumberOfNotFreeTickets == 0) {
                        $item->NumberOfNotFreeTickets = null;
                    }
                    if ($item->NumberOfFreeTickets == 0) {
                        $item->NumberOfFreeTickets = null;
                    }

                    return collect(
                        [
                            'Year' => $item->Year,
                            'NumberOfNotFreeTickets' => $item->NumberOfNotFreeTickets,
                            'NumberOfFreeTickets' => $item->NumberOfFreeTickets,
                        ]
                    );
                }
            )
        );

        $LineTotalCostPerYear = $this->getLineTotalCostPerYear(
            $TicketsPerYear->map(
                function ($item) {
                    return collect(['Year' => $item->Year, 'TotalCost' => $item->TotalCost]);
                }
            )
        );

        return view(
            'pages.Movie.ticketstats',
            [
                'TicketsTotal' => $TicketsTotal[0],
                'LineChartAverageTicketPricePerYear' => $LineChartAverageTicketPricePerYear,
                'BarChartNumberOfTicketsPerYear' => $BarChartNumberOfTicketsPerYear,
                'LineTotalCostPerYear' => $LineTotalCostPerYear,
            ]
        );
    }

    /**
     * @param Collection $dataTableRows
     *
     * @return mixed
     */
    public function getLineTotalCostPerYear(Collection $dataTableRows)
    {
        $dataTableColumns = [
            ['date', 'Year'],
            ['number', 'Total cost'],
        ];
        $name = 'LineTotalCostPerYear';
        $title = 'Total cost per year';
        $lineChart = (new Chart\LineChartController())
            ->createLineChart($name, $title, $dataTableColumns, $dataTableRows);

        return $lineChart;
    }

    /**
     * @param Collection $dataTableRows
     *
     * @return mixed
     */
    public function getBarChartNumberOfTicketsPerYear(Collection $dataTableRows)
    {
        $dataTableColumns = [
            ['date', 'Year'],
            ['number', 'Normal tickets'],
            ['number', 'Free tickets'],
        ];
        $name = 'BarChartNumberOfTicketsPerYear';
        $title = 'Number of tickets per year';
        $barChart = (new Chart\ColumnChartController())
            ->createColumnChart($name, $title, $dataTableColumns, $dataTableRows);

        $barChart->isStacked(true);

        return $barChart;
    }

    /**
     * @param Collection $dataTableRows
     *
     * @return mixed
     */
    public function getLineChartAverageTicketPricePerYear(Collection $dataTableRows)
    {
        $dataTableColumns = [
            ['date', 'Year'],
            ['number', 'Incl Free'],
            ['number', 'Excl Free'],
        ];
        $name = 'LineChartAverageTicketPricePerYear';
        $title = 'Average Ticket Price Per Year (Incl and Excl free tickets)';
        $lineChart = (new Chart\LineChartController())
            ->createLineChart($name, $title, $dataTableColumns, $dataTableRows);

        return $lineChart;
    }
}
