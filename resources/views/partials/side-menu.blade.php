<aside class="sidebar">
    <ul class="sidebar-nav">
	    <li><a href="{{route('lessons.list')}}" title="@lang('app.menu.my_lessons')"><i class="glyphicon glyphicon-education"></i> <span> @lang('app.menu.my_lessons')</span></a></li>
	    @if( Auth::user()->hasRole(EnumRole::STUDENT) )
	    <li><a href="{{route('teachers.list')}}" title="@lang('app.menu.teachers')"><i class="glyphicon glyphicon-globe"></i> <span> @lang('app.menu.teachers')</span></a></li>
	    @endif
        @if( Auth::user()->hasRole(EnumRole::ADMIN) )
        <li><a href="{{route('game-admin.list')}}" title="@lang('app.menu.games')"><i class="fa fa-gamepad"></i> <span> @lang('app.menu.games')</span></a></li>
        <li><a href="{{route('user-admin.list')}}" title="@lang('app.menu.users')"><i class="fa fa-users"></i> <span> @lang('app.menu.users')</span></a></li>
        <li><a href="{{route('user-admin.list-pre-registration')}}" title="@lang('app.menu.pre_registration')"><i class="fa fa-id-card"></i> <span> @lang('app.menu.pre_registration')</span></a></li>
        @endif
        @if( Auth::user()->hasRole(EnumRole::TEACHER) )
        <li><a href="{{route('my-games')}}" title="@lang('app.menu.my-games')"><i class="fa fa-gamepad"></i> <span> @lang('app.menu.my-games')</span></a></li>
        @endif
        @if( Auth::user()->hasRole(EnumRole::STUDENT) || Auth::user()->hasRole(EnumRole::TEACHER) )
	    <li><a href="{{route('profile')}}" title="@lang('app.menu.profile')"><i class="glyphicon glyphicon-user"></i> <span> @lang('app.navbar.profile')</span></a></li>
        @endif
	    <li>
	    	<a href="{{ route('logout') }}" title="@lang('app.navbar.logout')" onclick="event.preventDefault(); document.getElementById('logout-form-menu').submit();">
                <i class="glyphicon glyphicon-log-out"></i> <span> @lang('app.navbar.logout')</span>
            </a>
            <form id="logout-form-menu" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </li>
    </ul>
</aside>