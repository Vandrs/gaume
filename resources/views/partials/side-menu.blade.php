<aside class="sidebar">
    <ul class="sidebar-nav">
	    <li><a href="#" title="@lang('app.menu.my_lessons')"><i class="glyphicon glyphicon-education"></i> <span> @lang('app.menu.my_lessons')</span></a></li>
	    @if( Auth::user()->hasRole(EnumRole::STUDENT) )
	    <li><a href="{{route('teachers.list')}}" title="@lang('app.menu.teachers')"><i class="glyphicon glyphicon-globe"></i> <span> @lang('app.menu.teachers')</span></a></li>
	    @endif
	    <li><a href="#" title="@lang('app.navbar.profile')"><i class="glyphicon glyphicon-user"></i> <span> @lang('app.navbar.profile')</span></a></li>
    </ul>
</aside>