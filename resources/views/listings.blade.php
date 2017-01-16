
@extends('layouts.layout')

  @section('content')

  @if(count($listings) > 0)
    <div class="row">
      <div class="col-md-12"> <h1>{{ $listings->first()->type }} ebooks</h1> </div>
    </div>

    @foreach($listings->chunk(2) as $chunk)
      <div class="row">
        <div class="col-md-12">
          @foreach($chunk as $listing)
            <div class="verticalGap col-md-2"  style="border:1px dashed grey; padding:20px;">
              <img src="/images/{{ $listing->listing_image }}"> <br>
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
    @else
      <div class=" col-md-12 alert alert-warning">There is no ebook available for this category </div>
    @endif
  @endsection
