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
            <a class="navbar-brand" href="{{ URL::secure('') }}">Bogstag</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="{{ (Request::is('/') ? 'active' : '') }}">
                    <a href="{{ URL::secure('') }}"><i class="fa fa-home"></i> Home</a>
                </li>
                <li class="{{ (Request::is('about') ? 'active' : '') }}">
                    <a href="{{ URL::secure('about') }}">About</a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">Activity<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ URL::secure('activity/steps') }}">Steps</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">Server<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ URL::secure('server/email') }}">Email</a></li>
                    </ul>
                </li>
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
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li class="{{ (Request::is('auth/login') ? 'active' : '') }}"><a href="{{ URL::secure('auth/login') }}"><i
                                    class="fa fa-sign-in"></i> Login</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false"><i class="fa fa-user"></i> {{ Auth::user()->name }} <i
                                    class="fa fa-caret-down"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            @if(Auth::check())

                                <li>
                                    <a href="{{ URL::secure('admin/dashboard') }}"><i class="fa fa-tachometer"></i> Admin
                                        Dashboard</a>
                                </li>
                            @endif
                            <li role="presentation" class="divider"></li>

                            <li>
                                <a href="{{ URL::secure('auth/logout') }}"><i class="fa fa-sign-out"></i> Logout</a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
