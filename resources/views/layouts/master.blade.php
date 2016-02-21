<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@section('title') Bogstag @show</title>
    @section('meta_keywords')
        <meta name="keywords" content="bogstag, personal, api, dashboard"/>
    @show @section('meta_author')
        <meta name="author" content="Bogstag"/>
    @show @section('meta_description')
        <meta name="description"
              content="Trying to make a personal API and dashboard."/>
    @show
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="//fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ secure_asset('css/main.css') }}">
    @yield('styles')

            <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script type="text/javascript"
            src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script type="text/javascript"
            src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    @yield('headScripts')

</head>
<body>
@include('partials.nav')

<div class="container">
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @yield('content')
</div>
@include('partials.footer')

        <!-- Scripts -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
@yield('scripts')
</body>
</html>
