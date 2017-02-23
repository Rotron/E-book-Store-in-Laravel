<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use Schema;
use \GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Paypal\Paypal;
use App\Listing;
use App\User;
use Auth;

class ListingController extends Controller
{

    private $requiredTables = array('users', 'listings', 'orders');

    public function __construct(Request $request, Paypal $paypal)
    {
        $this->checkTables();
        $this->paypal = $paypal;
        $this->request = $request;
    }

    // Check if required tables exist..
    public function checkTables()
    {
        $errors = array();
        foreach($this->requiredTables as $table) {
            if(!Schema::hasTable($table)){
                $errors[] = 'Table ' . $table . ' does not exists..';
            }
        }
        if (!empty($errors)) {
            dd($errors);
        }
    }

    // Return PAID listings..
    public function paidListings()
    {
        $listings = Listing::where('listing_price', '>', 0)->paginate(10);
        return view('listings', ['listings' => $listings]);
    }

    // Return FREE listings..
    public function freeListings()
    {
        $listings = Listing::where('listing_price', '<=', 0)->paginate(10);
        return view('listings', ['listings' => $listings]);
    }

    // Expand PAID listing.
    public function paidListing(string $name, int $id)
    {
        if(Auth::user() != null) {
            $alreadyPurchased = Auth::user()->orders()->where('listing_id', $id)->get();
        }

        $listing = Listing::where('listing_price', '>', 0)->where('id', $id)->first();

        return view('listing', compact('listing', 'alreadyPurchased'));
    }

    // Expand FREE listing
    public function freeListing($name, $id)
    {
        $listing = Listing::where('listing_price', '<=', 0)->where('id', $id)->first();
        return view('listing', ['listing' => $listing]);
    }

    // Listing admin home
    public function admincp()
    {
        return view('admin/admincp', ['listings' => Listing::paginate(10)]);
    }

    // Return both PAID and FREE ebooks for homepage
    public function index(Request $request, $locale = 'en' )
    {
        \App::setLocale($locale);
        return view('index', ['listings' => Listing::paginate(10)]);
    }

    // Return paid listing by id
    public function listing($name, $id)
    {
        $listing =  Listing::where(['id' => $id, 'listing_type' => 'paid'])->get();
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
        $imageName = null;
        $listingName        = $this->request->input('listingName');
        $listingNameSlug    = str_slug($listingName, '-');
        $listingDescription = $this->request->input('listingDescription');
        $listingPrice       = $this->request->input('listingPrice');
        // $listingType        = $this->request->input('listingType');

        $messages = [
            'listingType.in' => 'Free and Paid are the only listing type accepted.',
            'listingPdf.required' => 'PDF file is missing in the upload',
            'listingImage.required' => 'Image file is missing. Please upload',
            'listingImage.dimensions' => 'Make sure your image is exactly 150x150px',
            // 'listingPrice.required_if' => 'Please fill Price field. You have chosen Paid listing',
        ];

        $rules = [
            'listingName' => 'required|max:100',
            'listingType' => 'in:Free,Paid',
            // 'listingPrice' => 'required_if:listingType,Paid|numeric|min:1',
            'listingDescription' => 'required',
            'listingPdf' => 'required|file',
            'listingImage' => 'dimensions:height=150,width=150',
        ];

        // Validate the form
        $validateResult = Validator::make($this->request->all(), $rules, $messages);

        if ($validateResult->fails()) {
            return redirect()->back()->withInput()->withErrors($validateResult);
        }

        // Convert spaces to dash(-)
        $pdfName = $this->addDashes('listingPdf');

        if ($this->request->file('listingImage')) {
            $imageName        = $this->addDashes('listingImage');
            $uploadImage      = $this->request->file('listingImage')->storeAs('images', $imageName);
        }

        // Upload new files, delete old file with same name..
        $uploadPdf        = $this->request->file('listingPdf')->storeAs('downloads', $pdfName);

        if ($uploadPdf) {
            // Save info to database
            $listing = new Listing;
            $listing->listing_name        = $listingName;
            $listing->listing_name_slug   = $listingNameSlug;
            $listing->listing_description = $listingDescription;

            $listing->listing_price = 0;

            $listing->listing_price       = $listingPrice;
            // $listing->listing_type        = $listingType;
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
    public function deleteOldPdf($id)
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


    // Check if uploaded file is new.
    public function isNewFile($id, $check)
    {
        $entryExists = Listing::where([
            'id' => $id,
            "$check" => $check,
            ])->first();

            if(!$entryExists) {
                return true;
            }
            return false;
        }

        /**
        /* Validate the request.
        /* Replace spaces in file names with dashes.
        /* Upload the file with modified name.
        /* If uploaded files are new(matched using file names in database)
        /* then upload them and delete the old files associated with same listing.
        /* Assign values to database.
        */
        public function editListing(Request $request)
        {
            $id           = $request->input('id');
            $listing      = Listing::findOrFail($id);
            $newPdfName   = null;
            $newImageName = null;

            $rules = [
                'listingName'         => 'required|max:100',
                'listingPrice'        => 'required_if:listingType,Paid|numeric|min:1',
                'listingDescription'  => 'required',
                'listingPdf'          => 'file',
                'listingImage'        => 'dimensions:height=150, width=150',
            ];

            $messages = [
                'listingImage.dimensions' => 'Image size must be 150 x 150px',
                'listingPrice.required_if' => 'Please fill Price field. You have chosen Paid listing',
            ];

            $validateResult = Validator::make($this->request->all(), $rules, $messages);

            if ($validateResult->fails()) {
                return redirect()->back()->withInput()->withErrors($validateResult);
            }

            // If file is uploaded add dashes to its name..
            if ($request->file('listingPdf')) {
                $newPdfName = $this->addDashes('listingPdf');
            }

            if ($request->file('listingImage')) {
                $newImageName = $this->addDashes('listingImage');
            }

            // Make sure a file is uploaded..
            if($newImageName != null) {

                // Make sure uploaded file is a new.
                if ($this->isNewFile($id, 'listing_image')) {

                    // Delete old file from storage associated with that listing
                    $this->deleteOldImage($id);

                    // Set new name in db
                    $listing->listing_image         = $newImageName;

                    // Upload the new file
                    if (!$request->file('listingImage')->storeAs('images', $newImageName)) {
                        throw new \Exception($newImageName . 'could not be uploaded');
                    }

                }

            }

            if ($newPdfName != null){

                if ($this->isNewFile($id, 'listing_pdf')) {

                    $this->deleteOldPdf($id);
                    $listing->listing_pdf = $newPdfName;

                    if (!$request->file('listingPdf')->storeAs('downloads', $newPdfName)) {
                        throw new \Exception($newPdfName . 'could not be uploaded');
                    }

                }

            }

            $listing->listing_name          = $this->request->input('listingName');
            $listing->listing_name_slug     = str_slug($this->request->input('listingName'));
            $listing->listing_description   = $this->request->input('listingDescription');

            $listing->listing_price         = $this->request->input('listingPrice');

            $listing->saveOrFail();

            return back()->with(['listingEdited' => 'Listing' . $this->request->input('listingName') . ' has been edited']);
        }

        /* Delete listing */
        public function deleteListings()
        {
            if (empty($this->request->input('ids'))) {
                return back()->with(['selectListing' => 'Select atleast one listing to delete']);
            }

            $listings = Listing::whereIn('id', $this->request->input('ids'));

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
