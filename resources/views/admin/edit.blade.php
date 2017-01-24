@extends('admin.layouts.layout')

@section('content')
<h1> Edit Listing </h1>

@foreach($errors->all() as $error)
    <div class="alert alert-warning">{{ $error }}  </div>
@endforeach

@if(session('listingEdited'))
  <div class="alert alert-success"> {{ session('listingEdited') }} </div>
@endif

<form method="POST" action="/admin/listing/edit" enctype="multipart/form-data">

  <input name="id" type="hidden" value="{{ $listing->id }}">

  <div class="form-group">
    <label for="listingName">Listing Name</label>
    <input value="{{ $listing->listing_name }}" id="listingName" class="form-control" type="text" name="listingName">
  </div>

  <div class="form-group">
    <label for="type"> Listing Type </label> <br>
    <select class="selectpicker" name="listingType" id="type">
      <option value="Free" @if(old('listingType') or $listing->type == 'Free') {{'selected'}} @endif>Free</option>
      <option value="Paid" @if(old('listingType') or $listing->type == 'Paid') {{'selected'}} @endif>Paid</option>
    </select>
  </div>

  <div class="form-group">
    <label for="listingDescription">Listing Description</label>
    <textarea rows="10" class="form-control" id="listingDescription" name="listingDescription">{{$listing->listing_description}}</textarea>
  </div>

  <div class="form-group">
    <label for="listingPrice">Price($)</label>
    <input value="{{ $listing->listing_price }}" class="form-control" type="number" name="listingPrice" min="1" max="1000">
  </div>

  <div class="form-group">
    <label for="listingPdf"> Upload PDF (Current: {{ $listing->listing_pdf }}) </label>
    <input id="listingPdf" class="btn btn-default" type="file" name="listingPdf" accept="application/pdf">
  </div>

  <div class="form-group">
    <label for="listingImage"> Upload Image - 150x150px (Current: {{ $listing->listing_image }}) </label> <br>
      <img src="/images/{{ $listing->listing_image }}"> <br> <br>
    <input id="listingImage" class="btn btn-default" type="file" name="listingImage" accept="image/*">
  </div>

  {{ csrf_field() }}
  {{ method_field('PATCH') }}
  <input class="btn btn-info" type="submit" value="Update">
</form> <br>

@endsection
