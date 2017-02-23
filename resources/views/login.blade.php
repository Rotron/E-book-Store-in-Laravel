
@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(session('notice'))
            <div class="alert alert-success col-md-12">
                {{ session('notice') }}
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

            <h1> @lang('login.login') </h1>
            <form class="form-group" id="adminLogin" method="POST" action="login">
                <div class="form-group">
                    <label for="username">@lang('login.username')</label>
                    <input value="{{ old('username') }}" name="username" placeholder="Username" class="form-control" type="text" id="adminUsername">
                </div>

                <div class="form-group">
                    <label for="password">@lang('login.password')</label>
                    <input name="password" placeholder="Password" class="form-control" type="password" id="adminPassword">
                </div>

                <div class="form-group">
                    <label for="remember">@lang('login.remember')</label>
                    <input name="remember" type="checkbox" id="remember">
                </div>

                {{ csrf_field() }}

                <input type="submit" value="@lang('login.login')" id="adminLogin" name="login" class="btn btn-primary">
            </form>
            <a href="/{{ App::getLocale() }}/reset-password">Forgot password?</a>
        </div>
    </div>
@endsection
