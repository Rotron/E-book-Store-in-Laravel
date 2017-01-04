
@extends('layouts.front-layout')

  @section('content')

    <div class="row">
      <div class="col-md-12"> <h1>Premium Money Making Ebooks </h1> </div>
    </div>

    @foreach($products->chunk(4) as $chunk)
      <div class="row">
        @foreach($chunk as $product)
          <div class="col-md-3">
            <form action="https://www.sandbox.paypal.com/webscr" method="post">
              <a href="/product/{{$product->slug}}/{{$product->id}}">
                 <b> {{ strtoupper($product->product_name) }} </b>
              </a> <br>
              {{ substr($product->product_description, 0, 10) }}
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
          </div>
        @endforeach
      </div>
    @endforeach

  @endsection
