@extends('layouts.layout')

@section('title', 'Recover password')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1> Change Password </h1>

            @foreach($errors->all() as $error)
                <div class="alert alert-warning">{{ $error }}</div>
            @endforeach

            <form method="POST" action="/change-password">
                <div class="form-group">
                    <label> Your New Password</label>
                    <input class="form-control" name="password" placeholder="New Password">
                    <input type="hidden" name="username" value="{{ $username }}">
                    <input type="hidden" name="reset_token" value="{{ $resetToken }}">
                </div>

                <input type="submit" class="btn btn-primary btn-lg" value="Change Password">
                {{ csrf_field() }}
            </form>

        </div>
    </div>
@endsection
