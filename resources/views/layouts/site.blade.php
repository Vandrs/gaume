<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    @if(config('app.index_pages'))
    <meta name="robots" content="index, follow">
    @else
    <meta name="robots" content="noindex, nofollow">
    @endif
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if(isset($pageTitle))
    <title>{{$pageTitle}}</title>
    @else
    <title>{{ config('app.name') }}</title>
    @endif
    <link rel="manifest" href="/manifest.json" />
    @include('partials.favicon')
    @if(config('app.env') == 'production')
    <link rel="canonical" href="{{url('')}}">
    @endif

    @if(isset($pageDescription))
    <meta name="description" content="{{$pageDescription}}">
    @endif

    @if(isset($pageTitle))
    <meta property="og:title" content="{{$pageTitle}}">
    @else
    <meta property="og:title" content="{{config('app.name')}}">
    @endif

    @if(isset($ogDescription))
    <meta property="og:description" content="{{$ogDescription}}">
    @endif

    <meta property="og:site_name" content="Monzy">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="pt_BR">
    <meta property="og:url" content="{{url('')}}">
    <meta property="og:image" content="{{url('/images/logo-share.png')}}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel="stylesheet">
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">

    @if(config('app.google_analytics_id'))
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', '{{config("app.google_analytics_id")}}', 'auto');
        ga('send', 'pageview');

    </script>
    @endif

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
            'pusherApp' => [
                'cluster' => Config::get('broadcasting.connections.pusher.options.cluster'),
                'key'     => Config::get('broadcasting.connections.pusher.key') 
            ],
            'user' => [
                'token' => Auth::guest() ? null : (Auth::user()->token() ? Auth::user()->token()->accessToken : Auth::user()->createToken('app')->accessToken),
                'name' => Auth::guest() ? null : Auth::user()->name,
                'id'  => Auth::guest() ? null : Auth::user()->id,
            ],
            'baseUrl' => url('/')
        ]) !!};
    </script>
</head>

@if(isset($bodyClass))
<body class="{{$bodyClass}}" data-spy="scroll" data-offset="65">
@else
<body>
@endif


    <div id="app">
        @include('partials.nav-bar-site')
        <article class="wrapper">
            <section class="main">
                <div class="content">
                @yield('content')
                </div>
            </section>
        </article>
    </div>
    <!-- Scripts -->
    @foreach (AssetLoader::getSiteScripts() as $script)
        <script src="{{ asset('js/'.$script) }}"></script>
    @endforeach
</body>
</html>
