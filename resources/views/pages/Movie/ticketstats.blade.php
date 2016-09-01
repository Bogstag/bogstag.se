@extends('layouts.master')
@section('title') Ticket Stats - Movie - @parent @stop
@section('content')
    <div class="row">
        <div class="page-header">
            <h2>Ticket Stats</h2>
        </div>
        <p>I have tried to save all my movie tickets since 1989, i have managed to keep most of them but some have
            disappeared over the years. The total number of tickets i have saved is {{ $TicketsTotal->TotalTickets }}
            pcs. {{ $TicketsTotal->NumberOfFreeTickets }} is free tickets (free mostly from loyalty programs, the main
            one started in the fall of 1999)
            and {{ $TicketsTotal->NumberOfNotFreeTickets }} i have payed for.</p>
        <p>Total money spent on the tickets
            is {{ $TicketsTotal->TotalCost }} SEK. That means that the average cost of a ticket (incl free)
            is {{ round($TicketsTotal->AverageCostPerTicketInclFree) }} SEK and if you don´t include the free tickets
            then the average price is {{ round($TicketsTotal->AverageCostPerTicketExclFree) }} SEK.</p>
    </div>
    <div class="row">
        <div id="LineTotalCostPerYear_div"></div>
        {!! Lava::render('LineChart', 'LineTotalCostPerYear', 'LineTotalCostPerYear_div') !!}

        <div id="LineChartAverageTicketPricePerYear_div"></div>
        {!! Lava::render('LineChart', 'LineChartAverageTicketPricePerYear', 'LineChartAverageTicketPricePerYear_div') !!}

        <div id="BarChartNumberOfTicketsPerYear_div"></div>
        {!! Lava::render('ColumnChart', 'BarChartNumberOfTicketsPerYear', 'BarChartNumberOfTicketsPerYear_div') !!}
    </div>
@endsection
