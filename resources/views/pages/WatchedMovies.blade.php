@extends('layouts.master')
@section('title') Watched Movies - @parent @stop
@section('content')
    <div class="row">
        <div class="page-header">
            <h2>Watched Movies <br/>
                <small>Latest 120 movies i have watched.</small>
            </h2>
        </div>
    </div>
    @if($WatchedMovies)
        <div class="container-fluid">
            @foreach($WatchedMovies as $WatchedMovie)
                <div class="col-sm-2">
                    <div class="thumbnail text-center">
                        <a href="{{ $WatchedMovie->slug }}" class="">
                            @if (empty($WatchedMovie->poster))
                                <h3>{{ $WatchedMovie->title }}</h3>
                            @else
                                <img class="img-responsive" src="{{ $WatchedMovie->poster }}"
                                     alt="{{ $WatchedMovie->title }} logo">
                            @endif
                        </a>
                    </div>
                </div>

            @endforeach
        </div>
    @endif
@endsection

@section('scripts')

@endsection

