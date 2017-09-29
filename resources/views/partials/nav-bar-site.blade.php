@if(isset($fixedNavBar) && $fixedNavBar)
<nav class="navbar navbar-default navbar-fixed-top navbar-collapse">
@else
<nav class="navbar navbar-default navbar-fixed-top navbar-collapse">
@endif
  <div class="container">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
            <span class="sr-only">Menu</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{url('/')}}">
          <img src="{{url('/images/logo-header.png')}}" title="Monzy" alt="Logo Monzy">
        </a>
    </div>
    <div id="navbar" class="navbar-collapse collapse navbar-right">
      <ul class="nav navbar-nav">
        <li><a href="#home">@lang('site.menu.home')</a></li>
        <li><a href="#about">@lang('site.menu.about')</a></li>
        <li><a href="#we">@lang('site.menu.we')</a></li>
        <li><a href="#contact">@lang('site.menu.contact')</a></li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">@lang('site.menu.register') <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{{url('/register')}}">@lang('site.menu.student')</a></li>
            <li><a href="#teacher">@lang('site.menu.teacher')</a></li>
          </ul>
        </li>
        <li><a href="{{url('/login')}}">@lang('site.menu.login')</a></li>
      </ul>
    </div>
  </div>
</nav>