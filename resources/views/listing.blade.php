@extends('layouts.layout')

@section('content')
  <h1> {{ $listing->listing_name }} </h1>
  <b> {{ $listing->listing_description }} </b>
  <img src="/{{ $listing->listing_image }}">
  <form action="https://www.sandbox.paypal.com/webscr" method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="seosatanforum-facilitator@gmail.com">
    <input type="hidden" name="amount" value="{{ $listing->listing_price }}">
    <input type="hidden" name="item_number" value="{{ $listing->id }}">
    <input type="hidden" name="item_name" value="{{ $listing->listing_name }}"> <br>
    <input
    type="image"
    src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_buynow_107x26.png"
    alt="Buy Now" />
  </form>
@endsection
