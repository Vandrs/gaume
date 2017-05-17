<aside class="sidebar">
    <ul class="sidebar-nav">
	    <li><a href="#"><i class="glyphicon glyphicon-education"></i> <span> @lang('app.menu.my_lessons')</span></a></li>
	    @if( Auth::user()->hasRole(EnumRole::STUDENT) )
	    <li><a href="#"><i class="glyphicon glyphicon-globe"></i> <span> @lang('app.menu.teachers')</span></a></li>
	    @endif
	    <li><a href="#"><i class="glyphicon glyphicon-user"></i> <span> @lang('app.navbar.profile')</span></a></li>
    </ul>
</aside>