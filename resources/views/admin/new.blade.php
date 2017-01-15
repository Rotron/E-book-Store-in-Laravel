
@extends('admin.layouts.layout')

@section('content')
  <h1> Add a new Listing </h1>

  @foreach($errors->all() as $error)
    <div class="alert alert-warning"> {{ $error }} </div>
  @endforeach

  @if(session('listingCreated'))
    <br> <div class="alert alert-success"> {{ session('listingCreated') }} </div>
  @endif

  <br>
  <form method="POST" action="/admin/listing/new" enctype="multipart/form-data">
    <div class="form-group">
      <label for="listingName">Listing Name</label>
      <input value="{{ old('listingName') }}" id="listingName" class="form-control" type="text" name="listingName"> <br>
    </div>

    <div class="form-group">
      <label for="type"> Listing Type </label> <br>
      <select class="selectpicker" name="listingType" id="type">
         <option value="Free">Free</option>
         <option value="Paid">Paid</option>
      </select>
    </div>

    <div class="form-group">
      <label for="listingDescription">Listing Description</label>
      <textarea rows="10" class="form-control" id="listingDescription" name="listingDescription">{{ old('listingDescription') }}</textarea>
    </div>

    <div class="form-group">
      <label for="listingPrice">Price($)</label>
      <input value="{{ old('listingPrice') }}" class="form-control" type="number" name="listingPrice" min="1" max="1000"> <br>
    </div>

    <div class="form-group">
      <label for="listingPdf"> Upload PDF </label>
      <input id="listingPdf" class="btn btn-default" type="file" name="listingPdf" accept="application/pdf"> <br>
    </div>

    <div class="form-group">
      <label for="listingImage"> Upload Image - 150x150px </label>
      <input id="listingImage" class="btn btn-default" type="file" name="listingImage" accept="image/*"> <br>
    </div>

    {{ csrf_field() }}
    <input class="btn btn-info" type="submit" value="Create Listing">
  </form>

@endsection
