
@extends('layouts.layout')

  @section('content')

    <div class="row">
      <div class="col-md-12">
        @if(session('notice'))
          <div class="alert alert-success"> {{ session('notice') }} </div>
        @endif

        <h1> Welcome to Free and paid ebooks collection </h1>
      </div>
    </div>

    @foreach($listings->chunk(5) as $chunk)
      <div class="row">
        <div class="col-md-12">
        @foreach($chunk as $listing)
          <div class="verticalGap col-md-2"  style="margin-left:10px; border:1px dashed grey; padding:20px;">

            @if($listing->listing_image != null)
              <img src="/images/{{ $listing->listing_image  }}"> <br>
            @else
              <img src="/default.png"> <br>
            @endif

            <hr>
            <a href="/listing/{{ ($listing->listing_price <= 0) ? 'free' : 'paid' }}/{{$listing->listing_name_slug}}/{{$listing->id}}">
              <b> {{ $listing->listing_name }} </b>
            </a> <br>
            <b> Price ${{ $listing->listing_price !== '' ? $listing->listing_price : 'Free' }} </b>
          </div>
        @endforeach
      </div>
      </div>
    @endforeach
    {{ $listings->links() }}

@endsection
