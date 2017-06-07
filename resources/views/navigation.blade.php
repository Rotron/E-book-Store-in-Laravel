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
        <li class="navbarStyle"><a href="/home">Home</a></li>
        <li class="navbarStyle"><a href="/listings/free">Free Ebook</a></li>
        <li class="navbarStyle"><a href="/listings/paid">Paid Ebooks</a></li>

        @if(Auth::guest())
          <li class="navbarStyle"> <a href="/user/login">Login</a> </li>
        @endif

        @if (Auth::user() and Auth::user()->role == 1)
          <li class="navbarStyle"> <a href="/admin/admincp">AdminCP</a> </li>
        @endif

        @if (Auth::user() and Auth::user()->role == 2)
          <li class="navbarStyle"> <a href="/user/usercp">UserCP</a> </li>
        @endif

        @if (Auth::check())
          <li class="navbarStyle"> <a href="/user/logout">Logout</a> </li>
        @endif

        @if (Auth::guest())
          <li class="navbarStyle"> <a href="/user/register">Register</a> </li>
        @endif

        <li class="navbarStyle"><a href="/contact">Contact us</a></li>
      </ul>
    </div>
  </nav>
