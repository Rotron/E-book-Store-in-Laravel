  <nav class="navbar navbar-default">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbarCollapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="nav navbar-nav">
        <li class="navbarStyle"><a href="/{{ \App::getLocale() }}/">@lang('navigation.home')</a></li>
        <li class="navbarStyle"><a href="/{{ \App::getLocale() }}/listings/free">@lang('navigation.premiumEbooks')</a></li>
        <li class="navbarStyle"><a href="/{{ \App::getLocale() }}/listings/paid">@lang('navigation.freeEbooks')</a></li>

        @if(Auth::guest())
          <li class="navbarStyle"> <a href="/{{ \App::getLocale() }}/user/login">@lang('navigation.login')</a> </li>
        @endif

        @if (Auth::user() and Auth::user()->role == 1)
          <li class="navbarStyle"> <a href="/{{ \App::getLocale() }}/admin/admincp">@lang('navigation.admincp')</a> </li>
        @endif

        @if (Auth::user() and Auth::user()->role == 2)
          <li class="navbarStyle"> <a href="/{{ \App::getLocale() }}/user/usercp">@lang('navigation.usercp')</a> </li>
        @endif

        @if (Auth::check())
          <li class="navbarStyle"> <a href="/{{ \App::getLocale() }}/user/logout">@lang('navigation.logout')</a> </li>
        @endif

        @if (Auth::guest())
          <li class="navbarStyle"> <a href="/{{ \App::getLocale() }}/user/register">@lang('navigation.register')</a> </li>
        @endif

        <li class="navbarStyle"><a href="/{{ \App::getLocale() }}/contact">@lang('navigation.contact')</a></li>
      </ul>
    </div>
  </nav>
