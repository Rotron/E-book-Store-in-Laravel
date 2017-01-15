
@extends('layouts.layout')

  @section('content')


    <div class="row">
      <div class="col-md-12"> <h1>Premium Money Making Ebooks </h1> </div>
    </div>

    @foreach($listings->chunk(2) as $chunk)
      <div class="row">
        <div class="col-md-12">
        @foreach($chunk as $listing)
          <div class="verticalGap col-md-2"  style="border:1px dashed grey; padding:20px;">
            <img src="{{ $listing->listing_image }}"> <br>
            <hr>
            <a href="/listing/{{$listing->listing_name_slug}}/{{$listing->id}}">
              <b> {{ $listing->listing_name }} </b>
            </a> <br>
            <b> ${{ $listing->listing_price }} </b>
          </div>
        @endforeach
      </div>
      </div>
    @endforeach
    {{ $listings->links() }}
  @endsection
