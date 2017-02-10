@extends('layouts.layout')

@section('title', 'Recover password')

@section('content')
    <div class="row">
        @foreach($errors->all() as $error)
            <div class="alert alert-warning">{{ $error }}</div>
        @endforeach
        
        <div class="col-md-12">
            <h2> Forgot Password?</h2>
            <form method="POST" action="/recover-password">

                <div class="form-group">
                    <label for="username"> Your username </label>
                    <input name="username" id="username" placeholder="username" class="form-control" required="" type="text">
                </di>
                <br>
                <div class="form-group">
                    <label for="email"> Your email </label>
                    <input name="email" id="email" placeholder="email address" class="form-control" required="" type="email">
                </div>

               <input class="btn btn-lg btn-primary" value="Send My Password" type="submit">

                {{ csrf_field() }}
            </form>
        </div>
    </div>
@endsection
