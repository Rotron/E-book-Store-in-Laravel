@extends('layouts.layout')

@section('title', 'Recover password')

@section('content')
<div class="row">

    <div class="col-md-12">

        @if(session('notice'))
        <div class="alert alert-success"> {{ session('notice') }} </div>
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

        <h2> Forgot Password?</h2>
        <form method="POST" action="/reset-password">

            <label for="username"> Your username </label>
            <input name="username" id="username" placeholder="username" class="form-control" required="" type="text">

            <br>

            <div class="form-group">
                <label for="email"> Your email </label>
                <input name="email" id="email" placeholder="email address" class="form-control" required="" type="email">
            </div>

            <input class="btn btn-lg btn-primary" value="Send Reset Link" type="submit">

            {{ csrf_field() }}
        </form>
    </div>
@endsection
