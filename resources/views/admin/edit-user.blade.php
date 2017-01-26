@extends('admin.layouts.layout')
@section('title', 'Edit User')

@section('content')

@if(session('notice'))
  <div class="alert alert-info"> {{ session('notice') }} </div>
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

  <h1> Edit User </h1>
  <form method="POST" action="/admin/user/edit/{{ $user->id }}">
    <div class="form-group">
      <label for="username">Username</label>
      <input class="form-control"  id="" type="text" name="username" value="{{ old('username') ? old('username') : $user->username }}">
    </div>

    <div class="form-group">
      <label for="email">Email</label>
      <input class="form-control" id="email" type="text" name="email" value="{{ old('email') ? old('email') : $user->email }}">
    </div>

    <div class="form-group">
      <label for="username">Password</label>
      <input class="form-control" id="password" type="text" name="password" placeholder="Change password..">
    </div>

    <div class="form-group">
      <label>Account Status</label>
      <input type="radio" name="confirmed" value="yes" {{$user->confirmation_code == null ? 'checked' : '' }}> Confirmed
      <input type="radio" name="confirmed" value="no" {{ $user->confirmation_code != null ? 'checked' : '' }}> Requires Confirmation
    </div>

    {{ csrf_field() }}
    {{ method_field('PATCH') }}

    <button class="btn btn-info" type="submit">Edit User</button>
  </form>

@endsection
