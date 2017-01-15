  <div id="logo"> <a href=""> <img src=""/> <h1> PDF MARKET </h1> </a> </div>

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
        <li class="navbarStyle"><a href="/">Home</a></li>
        <li class="navbarStyle"><a href="/listings/free">Free e-books</a></li>
        <li class="navbarStyle"><a href="/contact">Contact</a></li>
        <li class="navbarStyle"><a>Yo, {{ Auth::check() ? Auth::user()->username : 'Guest' }} </a></li>
      </ul>
    </div>
  </nav>
