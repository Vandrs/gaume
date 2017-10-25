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

    <link rel="dns-prefetch" href="//fonts.googleapis.com/">
    <link rel="dns-prefetch" href="//www.google-analytics.com/">
    <link rel="dns-prefetch" href="//stats.pusher.com/">
    
    <link rel="manifest" href="/manifest.json" />
    @include('partials.favicon')
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(isset($pageTitle))
    <title>{{$pageTitle | config('app.name') }}</title>
    @else
    <title>{{ config('app.name') }}</title>
    @endif
    <meta name="description" content="Monzy, o maior time de Experts em Jogos. Treine com maiores experts em e-Sports.">

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700i" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

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
            'vapidPublicKey' => Config::get('webpush.vapid.public_key'),
            'pusherApp' => [
                'cluster' => Config::get('broadcasting.connections.pusher.options.cluster'),
                'key'     => Config::get('broadcasting.connections.pusher.key') 
            ],
            'user' => [
                'token' => Auth::guest() ? null : (Auth::user()->token() ? Auth::user()->token()->accessToken : Auth::user()->createToken('app')->accessToken),
                'name'  => Auth::guest() ? null : Auth::user()->name,
                'id'    => Auth::guest() ? null : Auth::user()->id,
                'role'  => Auth::guest() ? null : Auth::user()->role->role
            ],
            'baseUrl' => url('/')
        ]) !!};
    </script>
</head>
<body>
    <div id="app">
        <confirmation-start-modal>
        </confirmation-start-modal>
        @include('partials.nav-bar')
        <article class="wrapper">
            <section class="main">
                <section class="content container" v-bind:class="{blur: isLoading}">
                @if(session()->has('msg_error'))
                <app-alert msg="{{session()->get('msg_error')}}" type="{{session()->get('class_error')}}">
                </app-alert>
                @else
                <app-alert>
                </app-alert>
                @endif
                @yield('content')
                </section>
            </section>
        </article>
    </div>
    <!-- Scripts -->
    @foreach (AssetLoader::getAppScripts() as $script)
        <script src="{{ asset('js/'.$script) }}"></script>
    @endforeach
</body>
</html>
