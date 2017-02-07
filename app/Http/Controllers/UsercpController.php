<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Order;
use App\Listing;

class UsercpController extends Controller
{
  public function __construct()
  {
    $this->middleware('redirectGuest');
  }

  // Listings can be deleted, but orders remain.
  // Make sure to soft delete listing and not actually delete so when retrieving
  // the listing info using Order model you can get it, if you actually deleted
  // a listing then you wouldn't be able to get that listing info..
  public function index()
  {
    // $userPurchasedListing = Order::where('user_id', Auth::user()->id)->first());7

    // Orders made by user
    $userOrders = Auth::user()->orders()->paginate();

    // Get listings thatmatch orders
    // $userOrderListings = Listing::wherein('id', $userOrders)->get();

    return view('usercp/usercp', ['userOrders' => $userOrders]);
  }

}
