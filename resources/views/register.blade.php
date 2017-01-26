@extends('layouts.layout')

@section('title', 'Register')

@section('content')
<script src='https://www.google.com/recaptcha/api.js'></script>

@if(session('notice'))
  <div class="alert alert-success"> {{ $notice }} </div>
@endif


@if(count($errors) > 0)
  <div class="alert alert-warning">
    <ul>
      @foreach($errors->all() as $error)
        <li> {{ $error }} </li>
      @endforeach
    </ul>
  </div>
@endif

<h1> Register </h1>
  <form method="POST" action="/user/register">
    <div class="form-group">
      <label for="username"> Username </label>
      <input name="username" class="form-control" id="username" type="text" value="{{ old('username') }}">
    </div>

    <div class="form-group">
      <label for="email"> Email </label>
      <input name="email" class="form-control" id="email" type="text" value="{{ old('email') }}">
    </div>

    <div class="form-group">
      <label for="password"> Password </label>
      <input name="password" class="form-control" id="password" type="text">
    </div>

    {{ csrf_field() }}
    <div class="g-recaptcha" data-sitekey="{{ config('app.gcaptcha_public') }}"></div>
    <input class="btn btn-info" type="submit" value="Register">
  </form>
@endsection
