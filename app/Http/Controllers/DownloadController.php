<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Listing;

class DownloadController extends Controller
{
  // Download files from storage folder
  public function download(int $id)
  {
    $listing = Listing::find($id);

    // Free listing
    if ($listing->listing_price <= 0) {
      return response()->download(storage_path() . '/app/downloads/' . $listing->listing_pdf);
    }

    // Paid listing
    $order = Auth::user()->orders()->where('listing_id', 2)->first();

    if (($order->status == 'Completed') && ((double) $order->mc_gross == (double) $order->listing->listing_price)) {
        return response()->download(storage_path() . '/app/downloads/' . $listing->listing_pdf);
    }

    throw new \Exception(Auth::user()->username . ' tried to retrieve paid listing');
  }

}
