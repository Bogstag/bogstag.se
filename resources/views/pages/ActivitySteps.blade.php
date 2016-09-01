@extends('layouts.master')
@section('title') Activity / Steps - @parent @stop
@section('content')
    <div class="row">
        <div class="page-header">
            <h2>Activity / Steps</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-9 col-md-9">
            <div id="stepcout_div"></div>
            {!! Lava::render('LineChart', 'stepcount', 'stepcout_div') !!}
        </div>
        <div class="col-lg-3 col-md-3">
            <div id="stepToday_div"></div>
            {!! Lava::render('DonutChart', 'stepToday', 'stepToday_div') !!}
        </div>
    </div>
    <div class="row">
        <div id="pacecount_div"></div>
        {!! Lava::render('LineChart', 'pacecount', 'pacecount_div') !!}

        <div id="stepduration_div"></div>
        {!! Lava::render('ColumnChart', 'stepduration', 'stepduration_div') !!}
    </div>
@endsection

@section('scripts')

@endsection

