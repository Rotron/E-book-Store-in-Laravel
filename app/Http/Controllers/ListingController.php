<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use \GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Paypal;
use App\Listing;

class ListingController extends Controller
{

  public function admincp()
  {
    return view('admin/admincp', ['listings' => Listing::paginate(10)]);
  }

  /* Main page, display all listings */
  public function index()
  {
    return view('index', ['listings' => Listing::paginate(10)]);
  }

  /* Expand a listing */
  public function listing($name, $id)
  {
    $listing = Listing::find($id);
    return view('listing', ['listing' => $listing]);
  }

  /* New listing view */
  public function newListingView()
  {
    return view('admin/new');
  }

  /* Create new listing */
  public function newListing(Request $request)
  {

    $this->validate($request, [
      'listingName' => 'required|max:50',
      'listingDescription' => 'required',
      'listingPrice' => 'required|numeric',
      'listingPdf' => 'required|file',
      'listingImage' => 'dimensions:height=150, width=150',
    ]);

    $uploadPdf = $request->file('listingPdf')->store('downloads');
    $uploadImage = $request->file('listingImage')->store('images');

    if ($uploadPdf && $uploadImage) {
      $listing = new Listing;
      $listing->listing_name = $request->input('listingName');
      $listing->listing_name_slug = str_slug($request->input('listing_name'));
      $listing->listing_description = $request->input('listingDescription');
      $listing->listing_price = $request->input('listingPrice');
      $listing->listing_pdf = $uploadPdf;
      $listing->listing_image = $uploadImage;
      $listing->saveOrFail();
      return back()->with(['listingCreated' => 'Listing' . $request->input('listingName') . 'has been uploaded']);
    }

    throw new exception('Files could not be uploaded');

  }


  // Return listings list
  public function listingView(Request $request)
  {
    return view('admin\listing', ['listings' => Listing::paginate(10)]);
  }


  // Look for image by id in database, get its name and delete from storage.
  public function deleteOldImage($id)
  {
    $imageNameById = Project::findOrFail($id)->listing_image();

    if (Storage::delete($imageNameById)) {
      return true;
    }

    return false;
  }

  // Return listing
  public function editListingView($id)
  {
    return view('admin/edit', ['listing' => Listing::findOrFail($id)]);
  }

  // Delete old image and update listing.
  public function editListing()
  {
    $this->validate($request, [
      'listingName'         => 'required|max:50',
      'listingDescription'  => 'required',
      'listingPrice'        => 'required|numeric',
      'listingPdf'          => 'required|file',
      'listingImage'        => 'dimensions:height=150, width=150',
    ]);

    $uploadPdf    = $request->file('listingPdf')->store('downloads');
    $uploadImage  = $request->file('listingImage')->store('images');

    if ($uploadPdf && $uploadImage) {
      if ($this->deleteOldImage($id)) {
        $listing = new Listing;
        $listing->listing_name          = $request->input('listingName');
        $listing->listing_name_slug     = str_slug($request->input('listing_name'));
        $listing->listing_description   = $request->input('listingDescription');
        $listing->listing_price         = $request->input('listingPrice');
        $listing->listing_pdf           = $uploadPdf;
        $listing->listing_image         = $uploadImage;
        $listing->saveOrFail();
        return back()->with(['listingEdited' => 'Listing' . $request->input('listingName') . 'has been edited']);
      }
    }
  }

  public function deleteListings(Request $request)
  {
    $this->validate($request, [
      'ids' => 'required'
    ]);

    if (Listing::whereIn('id', $request->input('ids'))->delete()) {
      return back()->with(['listingDeleted' => 'Listing(s) deleted']);
    }
    throw new \Exception('Could not delete the listing(s)');
  }

}
