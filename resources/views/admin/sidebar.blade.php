<nav id="adminSidebarContainer" class="navbar navbar-default">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
  </div>

  <div class="collapse navbar-collapse" id="myNavbar">
    <ul id="adminSidebarBody" class="nav navbar-nav" >
      <li id="adminSidebarHeading"> <a href="/admin/admincp"> {{ config('app.name') }} </a> </li>
      <li> <a href="/admin/logout"> Logout </a> </li>
    </ul>
  </div>
</nav>
