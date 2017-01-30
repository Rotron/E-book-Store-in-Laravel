@extends('layouts.layout')
@section('title', 'Contact')

@section('content')
  <script src='https://www.google.com/recaptcha/api.js'></script>

  <fieldset>
    @if(count($errors) > 0)
      <div class="alert alert-warning">
        <ul>
          @foreach($errors->all() as $error)
            <li> {{ $error }} </li>
          @endforeach
        </ul>
      </div>
    @endif

    @if(session('notice'))
      <div class="alert alert-info"> {{ session('notice') }} </div>
    @endif

    <br>

    <form method="post" action="/send-mail">
        <div class="form-group">
          <label for="name" class="label"> Name </label>
          <input placeholder="Your name" class="form-control" id="name" name="name" value="{{ old('name') }}">
        </div>

        <div class="form-group">
          <label for="email" class="label"> Your Email </label>
          <input  placeholder="Your email address where we will contact back" class="form-control" id="email" name="email" value="{{ old('email') }}">
        </div>

        <div class="form-group">
          <label for="message" class="label"> Message </label>
          <textarea placeholder="Your message please.." class="form-control" id="message" cols="50" rows="15" name="message">{{ old('message') }}</textarea>
        </div>

        <div class="g-recaptcha" data-sitekey="{{ config('app.gcaptcha_public') }}"></div> <br>
        {{csrf_field()}}
        <button class="btn btn-info" type="submit" value="Submit">Send Message</button> <br> <br>
    </form>
  </fieldset>
@endsection
