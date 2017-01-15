<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use \GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Paypal\Paypal;
use App\Listing;

class ListingController extends Controller
{

  public function __construct(Request $request, Paypal $paypal)
  {
    $this->paypal = $paypal;
    $this->request = $request;
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


  // Replace space with dashes and return.
  public function addDashes($inputName)
  {
    return str_replace(' ', '-', $this->request->file($inputName)->getClientOriginalName());
  }

  /* Create new listing */
  public function newListing(Request $request)
  {
    $listingName        = $this->request->input('listingName');
    $listingNameSlug    = str_slug($listingName, '-');
    $listingDescription = $this->request->input('listingDescription');
    $listingPrice       = $this->request->input('listingPrice');
    $listingType        = $this->request->input('listingType');

    $messages = [
      'listingType.in' => 'Free and Paid are the only listing type accepted.',
      'listingPdf.required' => 'PDF file is missing in the upload',
      'listingImage.required' => 'Image file is missing. Please upload',
      'listingImage.dimensions' => 'Make sure your image is exactly 150x150px'
    ];

    $rules = [
      'listingName' => 'required|max:50',
      'listingType' => 'in:Free,Paid',
      'listingDescription' => 'required',
      'listingPrice' => 'required|numeric',
      'listingPdf' => 'required|file',
      'listingImage' => 'required|dimensions:height=150, width=150',
    ];

    // Validate the form
    $validateResult = Validator::make($this->request->all(), $rules, $messages);

    if ($validateResult->fails()) {
      return redirect()->back()->withInput()->withErrors($validateResult);
    }

    // Convert spaces to dash(-)
    $pdfname = $this->addDashes('listingPdf');
    $imageName = $this->addDashes('listingImage');

    // Upload new files, delete old file with same name..
    if (Listing::where('listing_pdf', '!==', $pdfName) {
      $this->deleteOldImage($listingId);
      $uploadPdf        = $this->request->file('listingPdf')->storeAs('downloads', $pdfName);
    }

    if (Listing::findOrFail($id)->listing_image !== $imageName) {
      $this->deleteOldFile($listingId);
      $uploadImage      = $this->request->file('listingImage')->storeAs('images', $imageName);
    }

    // Save info to database
      $listing = new Listing;
      $listing->listing_name        = $listingName;
      $listing->listing_name_slug   = $listingNameSlug;
      $listing->listing_description = $listingDescription;
      $listing->listing_price       = $listingPrice;
      $listing->type                = $listingType;
      $listing->listing_pdf         = $pdfFilename;
      $listing->listing_image       = $imageFilename;
      $listing->saveOrFail();
      return back()->with(['listingCreated' => 'Listing' . $request->input('listingName') . 'has been uploaded']);

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


  /**
  /* Validate the request.
  /* Replace spaces in file names with dashes.
  /* Upload the file with modified name.
  /* If uploaded files are new(matched using file names in database and storage)
  /* then upload them and delete the old files associated with same listing.
  /* Assign values to database.
  */
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
      $pdfname = $this->addDashes('listingPdf');
    }

    if ($request->file('listingImage')) {
      $imageName = $this->addDashes('listingImage');
    }

    if (Listing::findOrFail($id)->listing_pdf !== $pdfName) {
      $this->deleteOldImage($listingId);
      $uploadPdf        = $request->file('listingPdf')->storeAs('downloads', $pdfName);
    }

    if (Listing::findOrFail($id)->listing_image !== $imageName) {
      $this->deleteOldFile($listingId);
      $uploadImage      = $request->file('listingImage')->storeAs('images', $imageFilename);
    }

    $listing = Listing::findOrFail($id);
    $listing->listing_name          = $request->input('listingName');
    $listing->listing_name_slug     = str_slug($request->input('listingName'));
    $listing->listing_description   = $request->input('listingDescription');
    $listing->listing_price         = $request->input('listingPrice');
    $listing->listing_pdf           = $pdfFilename;
    $listing->listing_image         = $imageFilename;

    $listing->saveOrFail();

    return back()->with(['listingEdited' => 'Listing' . $request->input('listingName') . 'has been edited']);
  }

  public function deleteListings(Request $request)
  {
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
