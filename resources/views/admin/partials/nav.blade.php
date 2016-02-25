<nav class="navbar navbar-inverse navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{URL::secure('admin/dashboard')}}">Bogstag Admin</a>
    </div>
    <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="{{ URL::secure('admin/profile') }}"><i class="fa fa-user fa-fw"></i> User Profile</a>
                </li>
                <li><a href="{{ URL::secure('admin/settings') }}"><i class="fa fa-gear fa-fw"></i> Settings</a>
                </li>
                <li class="divider"></li>
                <li><a href="{{ URL::secure('logout') }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                </li>
            </ul>
        </li>
    </ul>
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <a href="{{ URL::secure('') }}"><i class="fa fa-backward"></i> Go to frontend</a>
                </li>
                <li>
                    <a href="{{ URL::secure('admin/dashboard') }}">
                        <i class="fa fa-dashboard fa-fw"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ URL::secure('admin/externalapilimit') }}">
                        <i class="fa fa-bars fa-fw"></i> External Api Limits
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
