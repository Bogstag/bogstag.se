@extends('layouts.master')
@section('title') {{ $title }} - @parent @stop
@section('content')
    <div class="row">
        <div class="page-header">
            <h2>{{ $title }}</h2>
        </div>
    </div>
    @if($WatchedMovies)
        {!! $WatchedMovies->links() !!}
        <div class="container-fluid">
            <?php $ncachiv = 1 ?>
            <div class="row">
                @foreach($WatchedMovies as $WatchedMovie)
                    <div class="col-sm-2">
                        <div class="thumbnail text-center">
                            <a href="../{{ $WatchedMovie->slug }}" class="">
                                @if ($ticketsview)
                                    <img class="img-responsive" src="{{ $WatchedMovie->ticket_image_url }}"
                                         alt="{{ $WatchedMovie->title }} ticket">
                                    <div class="carousel-caption">
                                        <h4>{{ $WatchedMovie->title }}</h4>
                                    </div>
                                @else
                                    @if (empty($WatchedMovie->poster))
                                        <h3>{{ $WatchedMovie->title }}</h3>
                                    @else
                                        <img class="img-responsive" src="{{ $WatchedMovie->poster }}"
                                             alt="{{ $WatchedMovie->title }} logo">
                                    @endif
                                @endif
                            </a>
                        </div>
                    </div>
                    @if($ncachiv % 6 == 0)
            </div>
            <div class="row">
                @endif
                <?php $ncachiv++ ?>
                @endforeach
            </div>
        </div>
        {!! $WatchedMovies->links() !!}
    @endif
@endsection

@section('scripts')

@endsection

