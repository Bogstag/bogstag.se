@extends('layouts.master')
@section('title') Server / Email - @parent @stop
@section('content')
    <div class="row">
        <div class="page-header">
            <h2>Server / Email</h2>
        </div>
    </div>
    <div class="row">
        <div id="emailstat_div"></div>
        @columnchart('emailstat', 'emailstat_div')
    </div>
@endsection

@section('scripts')

@endsection

