@extends('layouts.master')
@section('title') Steam Games - @parent @stop
@section('content')
    <div class="row">
        <div class="page-header">
            <h2>Steam Games <br/>
                <small>I have played on average {{ $averageplaytimeperday }} hours per day for the last two weeks.
                </small>
            </h2>
        </div>
    </div>
    @if($SteamGames)
        <div class="list-group">
            <div class="container-fluid">
                <div class="row">
                    <div class=".col-xs-3 col-md-3"><strong>Game logo</strong></div>
                    <div class=".col-xs-5 col-md-5"><strong>Name</strong></div>
                    <div class=".col-xs-2 col-md-2"><strong>Hours played the last two weeks</strong></div>
                    <div class=".col-xs-2 col-md-2"><strong>Total hours played</strong></div>
                </div>

                @foreach($SteamGames as $SteamGame)
                    @if(! in_array($SteamGame->id, $SteamGame->getGamesWithNoStats()))
                        <a href="steam/{{ $SteamGame->id }}" name= "{{ $SteamGame->name }}" class="list-group-item">
                            @else
                                <a href="steam/{{ $SteamGame->id }}" class="list-group-item disabled">
                                    @endif
                                    <div class="row">
                                        <div class=".col-xs-3 col-md-3">
                                            <img class="media-object" src="{{ $SteamGame->image_logo_url }}"
                                                 alt="{{ $SteamGame->name }} logo">
                                        </div>

                                        <div class=".col-xs-5 col-md-5">
                                            <h4 class="media-heading">{{ $SteamGame->name }}
                                                @if ($SteamGame->playtime_forever == $SteamGame->playtime_2weeks && $SteamGame->playtime_forever > 0)
                                                    <span class="label label-success">New</span>
                                                @endif
                                            </h4>
                                        </div>


                                        <div class=".col-xs-2 col-md-2">
                                            <button type="button"
                                                    class="btn btn-default btn-lg">{{ round($SteamGame->playtime_2weeks/60) }}</button>
                                        </div>
                                        <div class=".col-xs-2 col-md-2">
                                            <button type="button"
                                                    class="btn btn-primary btn-lg">{{ round($SteamGame->playtime_forever/60) }}</button>
                                        </div>
                                    </div>
                                </a>
                        @endforeach
            </div>
        </div>
    @endif
    <small>Grey rows is games with no public achievements, it still may contain other information.</small>
@endsection

@section('scripts')

@endsection

