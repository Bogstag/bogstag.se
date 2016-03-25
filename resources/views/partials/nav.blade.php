<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <span class="navbar-brand">Bogstag</span>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="{{ (Request::is('/') ? 'active' : '') }}">
                    <a href="{{ URL::secure('') }}"><i class="fa fa-home"></i> Home</a>
                </li>
                <li class="{{ (Request::is('about') ? 'active' : '') }}">
                    <a href="{{ URL::secure('about') }}"><i class="fa fa-info"></i> About</a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false"><i class="fa fa-heart"></i> Activity<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ URL::secure('activity/steps') }}"><i class="fa fa-line-chart"></i> Steps</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false"><i class="fa fa-gamepad"></i> Games<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ URL::secure('game/steam') }}"><i class="fa fa-steam-square"></i> Steam</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false"><i class="fa fa-server"></i> Server<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ URL::secure('server/email') }}"><i class="fa fa-envelope-o"></i> Email</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false"><i class="fa fa-film"></i> Movie<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ URL::secure('movie/watched/all') }}"><i class="fa fa-eye"></i> Watched</a></li>
                        <li><a href="{{ URL::secure('movie/watched/cinema') }}"><i class="fa fa-ticket"></i> Watched in cinemas</a></li>
                    </ul>
                </li>

<!--

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">Api/v1<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ action('ApiDataPreviewController@index', $model = 'date') }}">Date</a></li>
                        <li><a href="{{ action('ApiDataPreviewController@index', $model = 'emailstat') }}">Email
                                Stat</a></li>
                        <li><a href="{{ action('ApiDataPreviewController@index', $model = 'step') }}">Step</a></li>
                    </ul>
                </li>
-->
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}"><i class="fa fa-sign-in"></i> Login</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ URL::secure('admin/dashboard') }}"><i class="fa fa-tachometer"></i> Admin
                                        Dashboard</a>
                                </li>

                            <li role="presentation" class="divider"></li>

                            <li>
                                <li><a href="{{ URL::secure('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
