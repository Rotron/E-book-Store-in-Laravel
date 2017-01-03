
@extends('layouts.front-layout')

  @section('content')
    <div class="col-md-12"> <h1> Premium Money Making Ebooks </h1> </div>

    @foreach($products as $product)
      <div class="col-md-3">
        <table class="table">
          <tr>
            <td>
            <form action="https://www.sandbox.paypal.com/webscr" method="post">
                <a href="/product/{{$product->product_name}}/{{$product->id}}">
                   <b> {{ strtoupper($product->product_name) }} </b>
                 </a>
                 <br>
                {{ $product->product_description }}
                <input type="hidden" name="cmd" value="_xclick">
                <input type="hidden" name="business" value="seosatanforum-facilitator@gmail.com">

                <input type="hidden" name="amount" value="{{ $product->price }}">
                <input type="hidden" name="item_number" value="{{ $product->id }}">
                <input type="hidden" name="item_name" value="{{ $product->product_name }}"> <br>
                <input
                type="image"
                src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_buynow_107x26.png"
                alt="Buy Now" />
              </form>
            </td>
          </tr>
        </table>
      </div>
    @endforeach

  @endsection
