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

  public function __construct()
  {
    $paypal = new Paypal;
  }

  // Return collection of free listings..
  public function freeListings()
  {
    return view(['listings' => Listing::where('type', 'free')->paginate(10)]);
  }

  // Return listing by id
  public function freeListing($id)
  {
    return view(['listing' => Listing::find($id)->paginate(10)]);
  }

  // Listing admin home
  public function admincp()
  {
    return view('admin/admincp', ['listings' => Listing::paginate(10)]);
  }

  // Return collectins of paid listings..
  public function index()
  {
    return view('index', ['listings' => Listing::where('type', 'paid')->paginate(10)]);
  }

  // Return paid listing by id
  public function listing($name, $id)
  {
    $listing =  Listing::where(['id' => $id, 'type' => 'paid'])->get();
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

    // Validate the form
    $this->validate($request, [
      'listingName' => 'required|max:50',
      'listingDescription' => 'required',
      'listingPrice' => 'required|numeric',
      'listingPdf' => 'required|file',
      'listingImage' => 'dimensions:height=150, width=150',
    ]);

    // Convert spaces to dash(-)
    $pdfFilename      = str_replace(' ', '-', $request->file('listingPdf')->getClientOriginalName());
    $imageFilename    = str_replace(' ', '-', $request->file('listingImage')->getClientOriginalName());

    // Upload the files
    $uploadPdf        = $request->file('listingPdf')->storeAs('downloads', $pdfFilename);
    $uploadImage      = $request->file('listingImage')->storeAs('images', $imageFilename);

    // Save info to database
    if ($uploadPdf && $uploadImage) {
      $listing = new Listing;
      $listing->listing_name = $request->input('listingName');
      $listing->listing_name_slug = str_slug($request->input('listingName'));
      $listing->listing_description = $request->input('listingDescription');
      $listing->listing_price = $request->input('listingPrice');
      $listing->listing_pdf = $pdfFilename;
      $listing->listing_image = $imageFilename;
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

  // Look for file by id in database, get its name and delete from storage.
  public function deleteOldFile($id)
  {
    $fileNamebyId = Project::findOrFail($id)->listing_pdf();

    if (Storage::delete($fileNamebyId)) {
      return true;
    }

    return false;
  }

  // Return listing
  public function editListingView($id)
  {
    return view('admin/edit', ['listing' => Listing::findOrFail($id)]);
  }

  // Update listing.
  public function editListing(Request $request)
  {
    $listingId = $request->input('id');

    $this->validate($request, [
      'listingName'         => 'required|max:50',
      'listingDescription'  => 'required',
      'listingPrice'        => 'required|numeric',
      'listingPdf'          => 'file',
      'listingImage'        => 'dimensions:height=150, width=150',
    ]);

    if ($request->file('listingPdf')) {
      // Convert spaces in file names to dash(-) my-new-pdf.pdf
      $pdfFilename      = str_replace(' ', '-', $request->file('listingPdf')->getClientOriginalName());

      // Upload the PDF file with modified name
      $uploadPdf        = $request->file('listingPdf')->storeAs('downloads', $pdfFilename);
    }

    if ($request->file('listingImage')) {
      $imageFilename    = str_replace(' ', '-', $request->file('listingImage')->getClientOriginalName());

      // Upload the image with modified name
      $uploadImage      = $request->file('listingImage')->storeAs('images', $imageFilename);
    }

    // Check if files were uploaded
    if ($uploadPdf || $uploadImage) {
      $listing = Listing::findOrFail($id);
      $listing->listing_name          = $request->input('listingName');
      $listing->listing_name_slug     = str_slug($request->input('listingName'));
      $listing->listing_description   = $request->input('listingDescription');
      $listing->listing_price         = $request->input('listingPrice');

      // Only assign new filename to table if uploaded file is new
      if (Listing::findOrFail($id)->listing_pdf !== $pdfFilename) {
        $listing->listing_pdf         = $pdfFilename;
        $this->deleteOldImage($listingId);
      }

      if (Listing::findOrFail($id)->listing_image !== $imageFilename) {
        $listing->listing_image         = $imageFilename;
        $this->deleteOldFile($listingId);
      }

      $listing->saveOrFail();

      return back()->with(['listingEdited' => 'Listing' . $request->input('listingName') . 'has been edited']);
    }
  }

  public function deleteListings(Request $request)
  {
    dd('stop');
    if (empty($request->input('ids'))) {
      return back()->with(['selectListing' => 'Select atleast one listing to delete']);
    }

    $listings = Listing::whereIn('id', $request->input('ids'));

    if ($listings->delete()) {

      $listings->each(function($listing){
        Storage::delete($listing->listing_image);
        Storage::delete($listing->listing_pdf);
      });

      return back()->with(['listingDeleted' => 'Listing(s) deleted']);
    }
    throw new \Exception('Could not delete the listing(s)');
  }

}
