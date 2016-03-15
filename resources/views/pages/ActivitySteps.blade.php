@extends('layouts.master')
@section('title') Activity / Steps - @parent @stop
@section('content')
    <div class="row">
        <div class="page-header">
            <h2>Activity / Steps</h2>
        </div>
    </div>
    <div class="row">
        <div id="stepcout_div"></div>
        @linechart('stepcount', 'stepcout_div')
    </div><div class="row">
        <div id="pacecount_div"></div>
        @linechart('pacecount', 'pacecount_div')
    </div><div class="row">
        <div id="stepduration_div"></div>
        @columnchart('stepduration', 'stepduration_div')
    </div>
@endsection

@section('scripts')

@endsection

