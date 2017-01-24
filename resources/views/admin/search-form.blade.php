<fieldset>
  <form method="POST" action="/admin/search">
    <br> <input id="search" class="form-control" name="query" placeholder="Search by username or email"> <Br>
    {{ csrf_field() }}
  </form>
</fieldset>
