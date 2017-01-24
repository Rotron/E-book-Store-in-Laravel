@extends('admin.layouts.layout')

@section('content')

  @if(count($errors) > 0)
  <ul class="alert alert-warning">
    @foreach($errors->all() as $error)
      <li> {{ $error }} </li>
    @endforeach
  </ul>
  @endif

  @include('admin.search-form')

  @if(count($usersList) > 0)
      <table class="table">
        <thead>
          <tr>
            <th> ID </th>
            <th> Username </th>
            <th> Email </th>
            <th> Confirmed? </th>
            <th> Total Purchases </th>
            <th> Edit </th>
          </tr>
        </thead>
        <tbody>
          @foreach($usersList as $user)
            <tr>
              <td> {{ $user->id }} </td>
              <td> {{ $user->username }} </td>
              <td> {{ $user->email }} </td>
              <td> {{ $user->confirmation_code == null ? 'Confirmed' : 'Not confirmed' }} </td>
              <td> {{ $user->orders()->count()  }} </td>
              <td> <a href="/admin/user/edit/{{ $user->id }}"> <button type="button" class="btn btn-info">Edit</button> </a> </td>
            </tr>
          @endforeach
        <tbody>
      </table>
      {{ method_field('DELETE') }}
      {{ csrf_field() }}
    @else
      <div class="alert alert-warning">No result <a href="/admin/users">Back</a></div>
    @endif

@endsection

<script>

  var userId = $('')
  $.ajax({
    'url' : '/admin/user/delete',
    'type' : 'POST',
    'data' : userId,
    'success' : function(data){

    }

  });

</script>
