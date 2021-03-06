@extends('layouts.master')
@section('title') {{ $SteamGame->name }} - Steam Game - @parent @stop
@section('content')

    @if ($SteamGame->Image_background)
        <?php $jumboStyle = 'style="color: white;background-size:100% auto;background-image:url(' . $SteamGame->Image_background . ');"'; ?>
    @else
        <?php $jumboStyle = ''; ?>
    @endif

    <div class="jumbotron" {!! $jumboStyle !!}>
        <h1>
            {{ $SteamGame->name }}
            @if ($SteamGame->playtimeforever == $SteamGame->playtime2weeks && $SteamGame->playtimeforever > 0)
                <span class="label label-success">New</span>
            @endif
        </h1>
        <p class="text-right">
            @if ($SteamGame->image_header)
                <img
                        src="{{ $SteamGame->image_header }}"
                        alt="{{ $SteamGame->name }} header image">
            @endif
        </p>
        <p class="text-right">
            @if ($SteamGame->website)
                <a class="btn btn-lg btn-info" href="{{ $SteamGame->website }}">
                    <i class="fa fa-globe fa-3x pull-left"></i>Official Site
                </a>
            @endif
            @if ($SteamGame->meta_critic_score)
                <a class="btn btn-lg
                        @if ($SteamGame->meta_critic_score >= 75)
                        btn-success
                        @elseif ($SteamGame->meta_critic_score < 50)
                        btn-danger
                        @else
                        btn-warning
                        @endif
                        " href="{{ $SteamGame->meta_critic_url }}">
                    <i class="fa fa-info-circle fa-3x pull-left"></i>metacritic<br>{{ $SteamGame->meta_critic_score }}
                </a>
            @endif


        </p>
    </div>
    <div class="container-fluid">
        @if (!$CompletedAchievements->isEmpty() || !$NotCompletedAchievements->isEmpty() || !$SteamGame->stats->isEmpty())
            <div class="row">
                <ul class="nav nav-pills nav-justified" role="tablist">
                    @if (!$CompletedAchievements->isEmpty())
                        <li class="active"><a href="#cachiv" role="tab" data-toggle="tab"><i
                                        class="fa fa-check-square-o"></i> Completed achievements</a>
                        </li>
                    @endif
                    @if (!$NotCompletedAchievements->isEmpty())
                        @if ($CompletedAchievements->isEmpty())
                            <li class="active">
                        @else
                            <li>
                                @endif
                                <a href="#ncachiv" role="tab" data-toggle="tab"><i class="fa fa-square-o"></i> Non
                                    completed achievements</a></li>
                        @endif
                        @if (!$SteamGame->stats->isEmpty())
                            <li><a href="#stats" role="tab" data-toggle="tab"><i class="fa fa-list-ol"></i> Stats</a>
                            </li>
                        @endif
                </ul>
                <div class="tab-content panel-body panel panel-default">
                    @if (!$CompletedAchievements->isEmpty())
                        <div class="tab-pane active" id="cachiv">
                            <div class="row">
                                <?php $cachiv = 1 ?>
                                @foreach($CompletedAchievements as $achievement)
                                    <div class="col-xs-4 col-sm-4 col-md-4 panel-body">
                                        <div class="media">
                                            <div class="media-left">
                                                @if ($achievement->icon_url)
                                                    <img class="media-object"
                                                         src="{{ $achievement->icon_url }}"
                                                         alt="{{ $SteamGame->name }} completed achievement icon">
                                                @endif
                                            </div>
                                            <div class="media-body">
                                                <h4 class="media-heading">{{ $achievement->display_name }}</h4>
                                                @if ($achievement->hidden)
                                                    <span class="label label-info">Hidden</span>
                                                @endif
                                                {{ $achievement->description }}
                                            </div>
                                        </div>
                                    </div>
                                    @if($cachiv % 3 == 0)
                            </div>
                            <div class="row">
                                @endif
                                <?php $cachiv++ ?>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if (!$NotCompletedAchievements->isEmpty())
                        <div class="tab-pane
                                @if ($CompletedAchievements->isEmpty())
                                active
                                @endif
                                panel-body" id="ncachiv">
                            <div class="row">
                                <?php $ncachiv = 1 ?>
                                @foreach($NotCompletedAchievements as $achievement)
                                    <div class="col-xs-4 col-sm-4 col-md-4 panel-body">
                                        <div class="media">
                                            <div class="media-left">
                                                <img class="media-object"
                                                     src="{{ $achievement->icon_gray_url }}"
                                                     alt="{{ $SteamGame->name }} locked achievement icon">
                                            </div>
                                            <div class="media-body">
                                                <h4 class="media-heading">{{ $achievement->display_name }}</h4>
                                                @if ($achievement->hidden)
                                                    <span class="label label-info">Hidden</span>
                                                @endif
                                                {{ $achievement->description }}
                                            </div>
                                        </div>
                                    </div>
                                    @if($ncachiv % 3 == 0)
                            </div>
                            <div class="row">
                                @endif
                                <?php $ncachiv++ ?>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if (!$SteamGame->stats->isEmpty())
                        <div class="tab-pane panel-body" id="stats">
                            <div class="row">
                                <?php $stats = 1 ?>
                                @foreach($SteamGame->stats as $stat)
                                    <div class="col-xs-4 col-sm-4 col-md-4 panel-body">
                                        <button class="btn btn-primary"
                                                type="button">{{ $stat->display_name }} <span
                                                    class="badge">{{ $stat->value }}</span>
                                        </button>
                                    </div>
                                    @if($stats % 3 == 0)
                            </div>
                            <div class="row">
                                @endif
                                <?php $stats++ ?>
                                @endforeach

                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <div class="row">
            @if ($SteamGame->movie_full_url)
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i
                                        class="fa fa-file-video-o"></i> {{ $SteamGame->movie_name }}</h3>
                        </div>
                        <div class="panel-body">
                            <video controls poster="{{ $SteamGame->movie_thumbnail }}"
                                   class="img-responsive center-block">
                                <source src="{{ $SteamGame->movie_full_url }}" type="video/webm">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </div>
                </div>
            @endif

            @if ($SteamGame->screenshot_path_full)
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-desktop"></i> Screenshot</h3>
                        </div>
                        <div class="panel-body">
                            <img src="{{ $SteamGame->screenshot_path_full }}"
                                 alt="{{ $SteamGame->name }} screenshot"
                                 class="img-responsive center-block">
                        </div>
                    </div>
                </div>
            @endif
        </div>


        @if ($SteamGame->about)
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-info"></i> About {{ $SteamGame->name }}</div>
                <div class="panel-body">
                    <small>{!! $SteamGame->about !!}</small>
                </div>
            </div>
        @endif

        @if ($SteamGame->legal_notice)
            <div class="well well-lg">
                <i class="fa fa-gavel fa-3x pull-left"></i>
                <small>{!! $SteamGame->legal_notice !!}</small>
            </div>
        @endif
    </div>
@endsection

@section('scripts')

@endsection

