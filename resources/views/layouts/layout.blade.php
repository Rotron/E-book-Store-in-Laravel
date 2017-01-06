<!doctype html>
<html>
  <head>
    <title> @yield('title') </title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.5/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="/css/global.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <div class="container">

      <div class="row">
        @section('header')
          @include('header')
        @show
      </div>

      @yield('content')

      <div class="row">
        @section('footer')
          @include('footer')
        @show
      </div>

    </div>
    <script src="/public/custom.js"></script>
  </body>
</html>
