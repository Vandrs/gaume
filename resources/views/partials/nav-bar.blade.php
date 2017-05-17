<nav class="navbar navbar-default navbar-fixed-top topbar">
    <div class="container-fluid">
        <div class="navbar-header">    
            <a class="navbar-brand" href="{{ url('/') }}">
                <span>{{ config('app.name') }}</span>
            </a>
            @if (!Auth::guest())
            <p class="navbar-text">
                <a href="#" class="sidebar-toggle">
                    <i class="glyphicon glyphicon-menu-hamburger"></i>
                </a>
            </p>
            @endif
        </div>
        <div class="navbar-collapse collapse" id="navbar-collapse-main">
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li><a href="{{ route('login') }}">@lang("app.navbar.login")</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="#">
                                    <i class="glyphicon glyphicon-user"></i> @lang('app.navbar.profile')
                                </a>
                            </li>
                            <li class="nav-divider"></li>
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    <i class="glyphicon glyphicon-log-out"></i> @lang('app.navbar.logout')
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>