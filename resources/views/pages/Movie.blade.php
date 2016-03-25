@extends('layouts.master')
@section('title') {{ $movie->title }} - Steam Game - @parent @stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <h1>{{ $movie->title }}
                @if ($movie->tagline)
                    <br/>
                    <small>{{ $movie->tagline }}</small>
                @endif
            </h1>
        </div>
        <div class="row">
            @if ($movie->clearart)
                <img class="img-responsive img-rounded center-block" src="{{ $movie->clearart }}"
                     alt="{{ $movie->title }} clearart image">
            @elseif ($movie->fanart)
                <img class="img-responsive img-rounded center-block" src="{{ $movie->fanart }}"
                     alt="{{ $movie->title }} fanart image">
            @endif
        </div>
    </div>
    <div class="clearfix">&nbsp;</div>

    @if ($movie->overview)
        <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-info"></i> About {{ $movie->title }}</div>
            <div class="panel-body">
                <p>{{ $movie->overview }}</p>
                @if ($movie->genres)
                    Genres: {{ $genrerow }}
                @endif
            </div>
        </div>
    @endif
    <a class="btn btn-lg btn-success" href="https://trakt.tv/movies/{{ $movie->slug }}">
        <i class="fa fa-link fa-3x pull-left"></i>Trakt.tv
    </a>
    @if ($movie->homepage)
        <a class="btn btn-lg btn-success" href="{{ $movie->homepage }}">
            <i class="fa fa-globe fa-3x pull-left"></i>Official<br/>Site
        </a>
    @endif
    @if ($movie->trailer)
        <a class="btn btn-lg btn-success" href="{{ $movie->trailer }}">
            <i class="fa fa-film fa-3x pull-left"></i>Trailer
        </a>
    @endif
    @if ($movie->certification)
        <span class="btn btn-lg btn-info">
            <i class="fa fa-certificate fa-3x pull-left"></i>Rated<br/>{{ $movie->certification }}
        </span>
    @endif
    @if ($movie->runtime)
        <span class="btn btn-lg btn-info">
            <i class="fa fa-clock-o fa-3x pull-left"></i>{{ $movie->runtime }}<br/>min
        </span>
    @endif
    @if ($movie->released)
        <span class="btn btn-lg btn-info">
            <i class="fa fa-calendar fa-3x pull-left"></i>Released<br/>{{ $movie->released }}
        </span>
    @endif
    @if ($movie->plays)
        <span class="btn btn-lg btn-info">
            <i class="fa fa-eye fa-3x pull-left"></i>{{ $movie->plays }}
        </span>
    @endif
    @if ($movie->ticket_datetime)
        <div class="clearfix">&nbsp;</div>
        <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-ticket"></i> Ticket for {{ $movie->title }}</div>
            <div class="panel-body">
                <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="media-left"><img class="img-responsive" src="{{ $movie->ticket_image_url }}" alt="..."></div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <span class="badge">{{ $movie->ticket_datetime }}</span>
                            Date and Time:
                        </li>
                        <li class="list-group-item">
                            <span class="badge">{{ $movie->ticket_price }}</span>
                            Price (SEK):
                        </li>
                        <li class="list-group-item">
                            <span class="badge">{{ $movie->ticket_row }}</span>
                            Row:
                        </li>
                        <li class="list-group-item">
                            <span class="badge">{{ $movie->ticket_seat }}</span>
                            Seat:
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')

@endsection

