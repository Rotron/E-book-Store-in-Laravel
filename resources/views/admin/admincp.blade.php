@extends('admin.layouts.layout')

@section('content')

  @if(count($errors) > 0)
    @foreach($errors->all() as $error)
      {{ $error }}
    @endforeach
  @endif

  @if(session('listingDeleted'))
    <br> <div class="alert alert-success"> {{ session('listingDeleted') }} </div>
  @endif

  @if(session('selectListing'))
    <br> <div class="alert alert-warning"> {{ session('selectListing') }} </div>
  @endif

  @if(count($listings) > 0)
    <form method="post" action="/admin/listing/delete">
      <table class="table">
        <thead>
          <tr>
            <th> ID </th>
            <th> Name </th>
            <th> Price </th>
            <th> Description </th>
            <th> Edit </th>
            <th> Check </th>
          </tr>
        </thead>
        <tbody>
          @foreach($listings as $listing)
            <tr>
              <td> {{ strToUpper($listing->id) }} </td>
              <td> <a href="/listing/{{$listing->listing_price <= 0 ? 'free' : 'paid'}}/{{$listing->listing_name_slug}}/{{ $listing->id }}"> {{ strToUpper($listing->listing_name) }} </td>
              <td> {{ $listing->listing_price }} </td>
              <td> {{ substr($listing->listing_description, 0, 50) }} </td>
              <td> <a href="/admin/listing/edit/{{ $listing->id }}"> <button type="button" class="btn btn-info">Edit</button> </a> </td>
              <td> <input type="checkbox" name="ids[]" value="{{ $listing->id }}"> </td>
            </tr>
          @endforeach
        <tbody>
      </table>
      <input class="btn btn-danger" type="submit" value="Delete">
      {{ method_field('DELETE') }}
      {{ csrf_field() }}
    </form>
  @else
    <br>
    <div class="alert alert-warning"> There is no listings.
      <a href="/admin/listing/new"> Create one now </a>
    </div>
  @endif

@endsection
