
@extends('admin.layouts.layout')

@section('content')
  @foreach($errors->all() as $error)
    {{ $error }}
  @endforeach

  @if(session('listingCreated'))
    <div class="alert alert-success"> {{ session('listingCreated') }} </div>
  @endif

  <form method="POST" action="/admin/listing/new" enctype="multipart/form-data">
    <label> </label>
    <input type="text" name="listingName"> <br>
    <textarea name="listingDescription"></textarea> <br>
    <input type="number" name="listingPrice" min="1" max="1000"> <br>
    <input type="file" name="listingPdf" accept="application/pdf"> <br>
    <input type="file" name="listingImage" accept="image/*"> <br>
    {{ csrf_field() }}
    <input type="submit" value="Create Listing">
  </form>

@endsection
