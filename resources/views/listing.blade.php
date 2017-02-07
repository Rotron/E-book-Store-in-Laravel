@extends('layouts.layout')

@section('content')
  <div class="row">
    <div class="col-md-12">
      <h1> {{ $listing->listing_name }} </h1>
    </div>

    <div class="col-md-2">
      @if($listing->listing_image != null)
        <img src="/images/{{ $listing->listing_image  }}"> <br>
      @else
        <img src="/default.png"> <br>
      @endif
    </div>

    <div class="col-md-6">
      {{ $listing->listing_description }} <br>
        @if($listing->listing_price >= 1)
          @if(Auth::user())
            @if(count($alreadyPurchased) <= 0)
              <form action="https://www.sandbox.paypal.com/webscr" method="post">
                <input type="hidden" name="cmd" value="_xclick">
                <input type="hidden" name="business" value="seosatanforum-facilitator@gmail.com">
                <input type="hidden" name="amount" value="{{ $listing->listing_price }}">
                <input type="hidden" name="item_number" value="{{ $listing->id }}">
                <input type="hidden" name="custom" value="{{ Auth::user()->id }}">
                <input type="hidden" name="item_name" value="{{ $listing->listing_name }}"> <br>
                <input
                type="image"
                src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_buynow_107x26.png"
                alt="Buy Now" />
              </form>
            @else
              <br> Seems like you have already purchased this PDF. <br> Check <a href="/user/usercp">UserCP</a> to download.
            @endif
          @else
            <br> Please<a href="/user/login"> login </a> or <a href="/user/register"> register </a> to purchase this product
          @endif
        @else
          <a href="/listing/download/{{ $listing->id }}">Download</a>
        @endif
    </div>

  </div>
@endsection
