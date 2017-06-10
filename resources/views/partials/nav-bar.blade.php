<nav class="navbar navbar-default navbar-fixed-top topbar">
    <div class="container-fluid">
        <div class="navbar-header">    
            <a class="navbar-brand" href="{{ url('/') }}">
                <span>{{ config('app.name') }}</span>
            </a>
            @if (!Auth::guest())
            <p class="navbar-text">
                <a href="#" v-on:click="toggleMenu" class="sidebar-toggle">
                    <i class="glyphicon glyphicon-menu-hamburger"></i>
                </a>
            </p>
            @endif
        </div>
        <div class="navbar-collapse collapse" id="navbar-collapse-main">
            <div class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                <a href="{{ route('login') }}">@lang("app.navbar.login")</a> | <a href="{{ route('register') }}">@lang("app.navbar.register")</a>
                @else
                <dropdown>
                  <a data-role="trigger" class="dropdown-toggle" type="button" aria-expanded="false">
                    @if(Auth::user()->photo_profile)<img class="nav-bar-photo" src="{{Auth::user()->getPhotoProfileUrl()}}" title="Auth::user()->nickname" alt="{{Auth::user()->name}}" />@endif {{ Auth::user()->nickname }} <span class="caret"></span>
                  </a>
                  <template slot="dropdown">
                    <li>
                        <a href="{{url('/app/home')}}">
                            <i class="glyphicon glyphicon-home"></i> @lang('app.navbar.home')
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="glyphicon glyphicon-user"></i> @lang('app.navbar.profile')
                        </a>
                    </li>
                    <li class="divider" role="separator"></li>
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
                  </template>
                </dropdown>
                @endif
            </div>
        </div>
    </div>
</nav>