<nav class="navbar navbar-default navbar-fixed-top topbar">
    <div class="container">
        <div class="navbar-header">    
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{url('/images/logo-header.png')}}" >
            </a>
        </div>
        <div class="navbar-collapse collapse" id="navbar-collapse-main">


            <div class="nav navbar-nav">
                <ul class="nav navbar-nav">
                    @if (Auth::check())
                        {{-- See resources/assets/js/components/NotificationsDropdown.vue --}}
                        <notifications-dropdown></notifications-dropdown>
                    @endif
                </ul>
            </div>


            <div class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                <a href="{{ route('login') }}">@lang("app.navbar.login")</a> | <a href="{{ route('register') }}">@lang("app.navbar.register")</a>
                @else
                <dropdown>
                  <a data-role="trigger" class="dropdown-toggle" type="button" aria-expanded="false">
                    @if(Auth::user()->photo_profile)<img class="nav-bar-photo" src="{{Auth::user()->getPhotoProfileUrl()}}" title="Auth::user()->nickname" alt="{{Auth::user()->name}}" />@endif {{ Auth::user()->nickname }} <i class="fa fa-bars"></i>
                  </a>
                  <template slot="dropdown">
                    <li>
                        <a href="{{url('/app/home')}}">
                            <i class="glyphicon glyphicon-home"></i> @lang('app.navbar.home')
                        </a>
                    </li>
                    <li><a href="{{route('lessons.list')}}" title="@lang('app.menu.my_lessons')"><i class="glyphicon glyphicon-education"></i> <span>@if(Auth::user()->hasRole(EnumRole::ADMIN)) @lang('app.menu.lessons')  @else @lang('app.menu.my_lessons') @endif </span></a></li>
                    @if( Auth::user()->hasRole(EnumRole::STUDENT) )
                    <li><a href="{{route('teachers.list')}}" title="@lang('app.menu.teachers')"><i class="fa fa-users"></i> <span> @lang('app.menu.teachers')</span></a></li>
                    <li><a href="{{route('pagseguro.redirect')}}" title="@lang('app.menu.wallet')"><i class="fa fa-credit-card-alt"></i> <span> @lang('app.menu.wallet')</span></a></li>
                    @endif
                    @if( Auth::user()->hasRole(EnumRole::ADMIN) )
                    <li><a href="{{route('game-admin.list')}}" title="@lang('app.menu.games')"><i class="fa fa-gamepad"></i> <span> @lang('app.menu.games')</span></a></li>
                    <li><a href="{{route('user-admin.list')}}" title="@lang('app.menu.users')"><i class="fa fa-users"></i> <span> @lang('app.menu.users')</span></a></li>
                    <li><a href="{{route('user-admin.list-pre-registration')}}" title="@lang('app.menu.pre_registration')"><i class="fa fa-id-card"></i> <span> @lang('app.menu.pre_registration')</span></a></li>
                    <li><a href="{{route('billing')}}" title="@lang('app.menu.billing')"><i class="fa fa-money"></i> <span> @lang('app.menu.billing')</span></a></li>
                    <li><a href="{{route('contact-list')}}" title="@lang('app.menu.messages')"><i class="fa fa-envelope"></i> <span> @lang('app.menu.messages')</span></a></li>
                    @endif
                    @if( Auth::user()->hasRole(EnumRole::TEACHER) )
                    <li><a href="{{route('my-games')}}" title="@lang('app.menu.my-games')"><i class="fa fa-gamepad"></i> <span> @lang('app.menu.my-games')</span></a></li>
                    @endif
                    @if( Auth::user()->hasRole(EnumRole::STUDENT) || Auth::user()->hasRole(EnumRole::TEACHER) )
                    <li><a href="{{route('profile')}}" title="@lang('app.menu.profile')"><i class="glyphicon glyphicon-user"></i> <span> @lang('app.navbar.profile')</span></a></li>
                    @endif
                    @if( !Auth::user()->hasRole(EnumRole::ADMIN) )
                    <li><a href="{{route('messages')}}" title="@lang('app.menu.messages')"><i class="fa fa-envelope"></i> <span> @lang('app.menu.messages')</span></a></li>
                    <li><a href="{{route('app.faq')}}" title="@lang('app.menu.faq')"><i class="fa fa-question-circle"></i> <span> @lang('app.menu.faq')</span></a></li>
                    @endif
                    <li>
                        <a href="{{ route('logout') }}" title="@lang('app.navbar.logout')" onclick="event.preventDefault(); document.getElementById('logout-form-menu').submit();">
                            <i class="glyphicon glyphicon-log-out"></i> <span> @lang('app.navbar.logout')</span>
                        </a>
                        <form id="logout-form-menu" action="{{ route('logout') }}" method="POST" style="display: none;">
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