@extends('layouts.front-layout')

@section('content')
  <h1> {{ $product->product_name }} </h1>
  <b> {{ $product->product_description }} </b>
  <form action="https://www.sandbox.paypal.com/webscr" method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="seosatanforum-facilitator@gmail.com">

    <input type="hidden" name="amount" value="{{ $product->product_price }}">
    <input type="hidden" name="item_number" value="{{ $product->id }}">
    <input type="hidden" name="item_name" value="{{ $product->product_name }}"> <br>
    <input
    type="image"
    src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_buynow_107x26.png"
    alt="Buy Now" />
  </form>
@endsection
