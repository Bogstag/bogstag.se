@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! $title !!} :: @parent @stop

{{-- Content --}}
@section('main')
    <h3>
        {{$title}}
    </h3>
    <div class="row">
        @if ($externalApiLimits)
            <div class="col-lg-6 col-md-6">
                <div class="panel-body">
                    @foreach ($externalApiLimits->where('external_api_limit_interval', 'Day') as $externalApiLimit)
                        <div class="panel panel-default">
                            <div class="panel-heading">{{$externalApiLimit->external_api_name}}
                                - {{$externalApiLimit->external_api_limit_interval}}
                                - {{$externalApiLimit->limit_interval_start}}</div>
                            <div class="panel-body">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar"
                                         aria-valuenow="{{ ($externalApiLimit->external_api_count / $externalApiLimit->external_api_limit) * 100 }}"
                                         aria-valuemin="0" aria-valuemax="100"
                                         style="width: {{ ($externalApiLimit->external_api_count / $externalApiLimit->external_api_limit) * 100 }}%;">
                                        {{ ($externalApiLimit->external_api_count / $externalApiLimit->external_api_limit) * 100 }}
                                        % / {{$externalApiLimit->external_api_limit}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="panel-body">
                    @foreach ($externalApiLimits->where('external_api_limit_interval', '5min') as $externalApiLimit)
                        <div class="panel panel-default">
                            <div class="panel-heading">{{$externalApiLimit->external_api_name}}
                                - {{$externalApiLimit->external_api_limit_interval}}
                                - {{$externalApiLimit->limit_interval_start}}</div>
                            <div class="panel-body">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar"
                                         aria-valuenow="{{ ($externalApiLimit->external_api_count / $externalApiLimit->external_api_limit) * 100 }}"
                                         aria-valuemin="0" aria-valuemax="100"
                                         style="width: {{ ($externalApiLimit->external_api_count / $externalApiLimit->external_api_limit) * 100 }}%;">
                                        {{ ($externalApiLimit->external_api_count / $externalApiLimit->external_api_limit) * 100 }}
                                        % / {{$externalApiLimit->external_api_limit}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
