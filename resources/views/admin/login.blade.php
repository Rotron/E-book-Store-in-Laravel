
@extends('layouts.layout')

<div class="row">

  @section('content')

  <h1> Login to Admin </h1>
    @if(session('loginFailed'))
      <div class="alert alert-warning col-md-12">
        {{session('loginFailed')}}
      </div>
    @endif

    @if(session('loggedOut'))
      <div class="alert alert-success col-md-12">
        {{ session('loggedOut') }}
      </div>
    @endif

    @if(count($errors) > 0)
      <div class="alert alert-warning col-md-12">
        <ul>
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form class="form-group" id="adminLogin" method="POST" action="admin/login">
      <div class="form-group">
        <label for="username">Username</label>
        <input name="username" placeholder="Username" class="form-control" type="text" id="adminUsername">
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input name="password" placeholder="Password" class="form-control" type="password" id="adminPassword">
      </div>

       {{ csrf_field() }}

       <input type="submit" value="Login" id="adminLogin" name="adminLogin" class="btn btn-primary">
     </form>

  @endsection
</div>
