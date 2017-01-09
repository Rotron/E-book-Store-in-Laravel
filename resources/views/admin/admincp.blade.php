@extends('admin.layouts.layout')

@section('content')
  @if(count($errors) > 0)
    @foreach($errors->all() as $error)
      {{ $error }}
    @endforeach
  @endif

  @if(session('deleteListings'))
    {{ session('deleteListings') }}
  @endif
  <form method="post" action="/admin/listing/delete">
    <table class="table">
      <thead>
        <tr>
          <th> ID </th>
          <th> Listing Name </th>
          <th> Listing Description </th>
          <th> Edit </th>
          <th> Check </th>
        </tr>
      </thead>
      <tbody>
        @foreach($listings as $listing)
          <tr>
            <td> {{ strToUpper($listing->id) }} </td>
            <td> <a href="/listing/{{$listing->listing_name_slug}}/{{ $listing->id }}"> {{ strToUpper($listing->listing_name) }} </td>
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
@endsection