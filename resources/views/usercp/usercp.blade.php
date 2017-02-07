@extends('usercp.usercp-layout')
@section('title', 'UserCP')

@section('content')
  <h1> Purchases </h1>
  @foreach($userOrders as $order)

    <h3> <a href="/listing/paid/{{ $order->listing->listing_name_slug }}/{{ $order->listing_id }}"> {{ $order->listing->listing_name }} </a> </h3>
    @if ($order->status == 'Pending')
      Payment status is pending.
    @endif

    @if($order->status == 'Completed')
      Download: <a href="/listing/download/{{ $order->listing_id }}"> {{ $order->listing->listing_name }} </a>
    @endif
  <hr>

  @endforeach
@endsection
