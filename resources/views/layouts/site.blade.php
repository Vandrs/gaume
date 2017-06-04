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
    <link rel="manifest" href="/manifest.json" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(isset($pageTitle))
    <title>{{$pageTitle." | ".config('app.name') }}</title>
    @else
    <title>{{ config('app.name') }}</title>
    @endif

    <!-- Styles -->
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
            'user' => [
                'token' => Auth::guest() ? null : (Auth::user()->token() ? Auth::user()->token()->accessToken : Auth::user()->createToken('app')->accessToken),
                'name' => Auth::guest() ? null : Auth::user()->name,
                'id'  => Auth::guest() ? null : Auth::user()->id,
            ],
            'baseUrl' => url('/')
        ]) !!};
    </script>
</head>
<body>
    <div id="app">
        @include('partials.nav-bar')
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
