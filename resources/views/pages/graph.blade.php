@extends('layouts.master')
@section('title') Graph - @parent @stop
@section('content')
    <div class="row">
        <div class="page-header">
            <h2>Graph</h2>
        </div>
    </div>
    <div class="row">
        <div id="stepcout_div"></div>
        @linechart('stepcount', 'stepcout_div')
        <div id="pacecount_div"></div>
        @linechart('pacecount', 'pacecount_div')
        <div id="stepduration_div"></div>
        @columnchart('stepduration', 'stepduration_div')




    </div>
@endsection

@section('scripts')

@endsection

