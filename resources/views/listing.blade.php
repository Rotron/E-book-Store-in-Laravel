@extends('layouts.layout')

@section('content')

  <div class="row">
    <div class="col-md-12">
      <h1> {{ $listing->listing_name }} </h1>
    </div>

    <div class="col-md-2">
      <img src="/images/{{ $listing->listing_image }}">
    </div>

    <div class="col-md-6">
      {{ $listing->listing_description }}
      <form action="https://www.sandbox.paypal.com/webscr" method="post">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value="seosatanforum-facilitator@gmail.com">
        <input type="hidden" name="amount" value="{{ $listing->listing_price }}">
        <input type="hidden" name="item_numberx" value="{{ $listing->id }}">
        <input type="hidden" name="item_namex" value="{{ $listing->listing_name }}"> <br>
        <input
        type="image"
        src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_buynow_107x26.png"
        alt="Buy Now" />
      </form>
    </div>

  </div>
@endsection
