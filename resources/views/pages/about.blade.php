@extends('layouts.master')
@section('title') About - @parent @stop
@section('content')
    <div class="row">
        <div class="page-header">
            <h1 id="personal-dashboard-and-data-collecting.">Personal dashboard and data collecting.</h1>
        </div>
        <p><a href="https://travis-ci.org/Bogstag/bogstag.se"><img src="https://travis-ci.org/Bogstag/bogstag.se.svg"
                                                                   alt="Build Status"/></a> <a
                    href="https://insight.sensiolabs.com/projects/fd0209c5-cd30-43f4-ae9b-dd72790cdbb4"><img
                        src="https://insight.sensiolabs.com/projects/fd0209c5-cd30-43f4-ae9b-dd72790cdbb4/mini.png"
                        alt="SensioLabsInsight"/></a> <a
                    href="https://www.versioneye.com/user/projects/56c134f318b271003b391391"><img
                        src="https://www.versioneye.com/user/projects/56c134f318b271003b391391/badge.svg?style=flat"
                        alt="Dependency Status"/></a> <a
                    href="https://codecov.io/github/Bogstag/bogstag.se?branch=master"><img
                        src="https://codecov.io/github/Bogstag/bogstag.se/coverage.svg?branch=master" alt="codecov.io"/></a>
            <a href="https://styleci.io/repos/42884250"><img src="https://styleci.io/repos/42884250/shield"
                                                             alt="StyleCI"/></a></p>
        <p>This read me is more so i can remember stuff. This project if for me to learn, but there is nothing that is
            unique for me so if you would like to fork, use it as you own or help me i be glad.</p>
        <h2 id="integrations">Integrations</h2>
        <h3 id="steam-api">Steam API</h3>
        <p>Integrates to steam api to pull games i own and to update games im playing and new games. The external api
            counter is is only counting, so there is nothing to stop you from doing too many calls to api. When using
            this in local environment it only calls the ap one time and cache it under storage/app/SteamApi.</p>
        <h4 id="first-time-setup">First time setup</h4>
        <p>If you dont own a lot of games, you can load everything from a artisan command:</p>
        <pre><code>php artisan steamapi:game load</code></pre>
        <p>After that the scheduled task take care of the rest.</p>
    </div>
@endsection
