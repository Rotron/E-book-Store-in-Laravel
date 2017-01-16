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

  // Return PAID listings..
  public function paidListings()
  {
    $listings = Listing::where('type', 'paid')->paginate(10);
    return view('listings', ['listings' => $listings]);
  }

  // Return FREE listings..
  public function freeListings()
  {
    $listings = Listing::where('type', 'free')->paginate(10);
    return view('listings', ['listings' => $listings]);
  }

  // Expand PAID listing
  public function paidListing($name, $id)
  {
    $listing = Listing::where(['type' => 'paid', 'id' => $id])->first();
    return view('listing', ['listing' => $listing]);
  }

  // Expand FREE listing
  public function freeListing($name, $id)
  {
    $listing = Listing::where(['type' => 'free', 'id' => $id]);
    return view('listing', ['listing' => $listing]);
  }

  // Return all listing for admin dashboard
  public function listings()
  {
    return view(['admin/admincp' => Listing::paginate(10)]);
  }

  // Listing admin home
  public function admincp()
  {
    return view('admin/admincp', ['listings' => Listing::paginate(10)]);
  }

  // Return both PAID and FREE ebooks
  public function index()
  {
    return view('index', ['listings' => Listing::paginate(10)]);
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
    $pdfName = $this->addDashes('listingPdf');
    $imageName = $this->addDashes('listingImage');

    // Upload new files, delete old file with same name..
    $uploadPdf        = $this->request->file('listingPdf')->storeAs('downloads', $pdfName);

    $uploadImage      = $this->request->file('listingImage')->storeAs('images', $imageName);

    if ($uploadPdf && $uploadImage) {
      // Save info to database
      $listing = new Listing;
      $listing->listing_name        = $listingName;
      $listing->listing_name_slug   = $listingNameSlug;
      $listing->listing_description = $listingDescription;
      $listing->listing_price       = $listingPrice;
      $listing->type                = $listingType;
      $listing->listing_pdf         = $pdfName;
      $listing->listing_image       = $imageName;
      $listing->saveOrFail();

      return back()->with(['listingCreated' => 'Listing' . $request->input('listingName') . ' has been uploaded']);
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
    $imageNameById = Listing::findOrFail($id)->listing_image;

    if (Storage::delete($imageNameById)) {
      return true;
    }

    return false;
  }

  // Look for file by id in database, get its name and delete from storage.
  public function deleteOldFile($id)
  {
    $fileNamebyId = Listing::findOrFail($id)->listing_pdf;

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
    $listing = Listing::findOrFail($id);
    $id = $request->input('id');
    $newPdfName = null;
    $newImageName = null;

    $this->validate($request, [
      'listingName'         => 'required|max:50',
      'listingDescription'  => 'required',
      'listingPrice'        => 'required|numeric',
      'listingPdf'          => 'file',
      'listingImage'        => 'dimensions:height=150, width=150',
    ]);

    // If file is uploaded add dashes to its name..
    if ($request->file('listingPdf')) {
      $newPdfName = $this->addDashes('listingPdf');
    }

    if ($request->file('listingImage')) {
      $newImageName = $this->addDashes('listingImage');
    }


    // Make sure a file is uploaded..
    if($newImageName != null) {

      // Make sure uploaded file is a new file, match name with filename in storage
      // This section needs changing. Check filename in storage instead of database.
      if (!Listing::where(['id' => $id, 'listing_image' => $newImageName])) {

        // Delete old file from storage
        $this->deleteOldImage($id);

        // Set new name in db
        $listing->listing_image         = $imageName;

        // Upload the new file
        if (!$request->file('listingPdf')->storeAs('downloads', $newImageName)) {
          throw new \Exception($newImageName . 'could not be uploaded');
        }

      }

    }

    if ($newPdfName != null){

      if (!Listing::where(['id' => $id, 'listing_image' => $newPdfName])) {

        $this->deleteOldFile($id);
        $listing->listing_pdf = $pdfName;

        if (!$request->file('listingPdf')->storeAs('downloads', $newPdfName)) {
          throw new \Exception($newPdfName . 'could not be uploaded');
        }

      }

    }

    $listing->listing_name          = $request->input('listingName');
    $listing->listing_name_slug     = str_slug($request->input('listingName'));
    $listing->listing_description   = $request->input('listingDescription');
    $listing->listing_price         = $request->input('listingPrice');
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
