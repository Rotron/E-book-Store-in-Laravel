@extends('usercp.usercp-layout')
@section('title', 'UserCP')

@section('content')
  <h1> Purchases </h1>
  @foreach($orders as $order)
    {{ $order->listing_id }}
  @endforeach
@endsection
