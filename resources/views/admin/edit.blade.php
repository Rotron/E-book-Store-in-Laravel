@extends('admin.layouts.layout')

@section('content')

@foreach($errors->all() as $error)
  {{ $error }}
@endforeach

@if(session('listingUpdated'))
  <div class="alert alert-success"> {{ session('listinUpdated') }} </div>
@endif

<form method="POST" action="/admin/listing/update" enctype="multipart/form-data">
  <label> </label>
  <input type="text" name="listingName" value="{{ $listing->listing_name }}"> <br>
  <textarea name="listingDescription">{{ $listing->listing_description }}</textarea> <br>
  <input type="number" name="listingPrice" min="1" max="1000" value="{{ $listing->listing_price }}"> <br>
  <input type="file" name="listingPdf" accept="application/pdf"> <br>
  <input type="file" name="listingImage" accept="image/*"> <br>
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
  <input type="submit" value="Create Listing">
</form>

@endsection
