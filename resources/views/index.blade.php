
@extends('layouts.layout')

  @section('content')

    <div class="row">
      <div class="col-md-12"> <h1>Premium Money Making Ebooks </h1> </div>
    </div>

    @foreach($listings->chunk(4) as $chunk)
      <div class="row">
        @foreach($chunk as $listing)
          <div class="verticalGap col-md-3">
            <form action="https://www.sandbox.paypal.com/webscr" method="post">
              <a href="/listing/{{$listing->listing_name_slug}}/{{$listing->id}}">
                 <b> {{ strtoupper($listing->listing_name) }} </b>
              </a> <br>
              <img src="{{ $listing->listing_image }}">
              {{ substr($listing->listing_description, 0, 50) }}
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
          </div>
        @endforeach
      </div>
    @endforeach
    {{ $listings->links() }}
  @endsection
