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
    <title>{{$pageTitle | config('app.name') }}</title>
    @else
    <title>{{ config('app.name') }}</title>
    @endif

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
            'vapidPublicKey' => Config::get('webpush.vapid.public_key'),
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
        <article class="wrapper" v-bind:class="{toggled: menuToggled}">
            @include('partials.side-menu')
            <section class="main">
                <section class="content" v-bind:class="{blur: isLoading}">
                <app-alert>
                </app-alert>
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
