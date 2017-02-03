<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Order;

class UsercpController extends Controller
{
  public function __construct()
  {
    $this->middleware('redirectGuest');
  }

  // Listings can be deleted, but orders remain.
  // Make sure to soft delete listing and not actually delete so when retrieving
  // the listing info using Order model you can get it, if you actually deleted
  // A listing then you wouldn't be able to get that listing info.. 
  public function index()
  {
    dd(Order::where('user_id', Auth::user()->id)->first());
    $userOrders = Auth::user()->orders;
    dd($userOrders);
    $userOrderListing = Listing::wherein('id', $userOrders);
    return view('usercp/usercp', ['orders' => '' ]);
  }

}
