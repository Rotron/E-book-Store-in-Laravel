<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\Storage;
use \GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\PaypalIPN;

class ProductsController extends Controller
{

  public function index()
  {
    return view('index', ['products' => Product::paginate(10)]);
  }

  public function product($slug, $id)
  {
    $products = Product::find($id);
    return view(['products' => $products]);
  }

  public function ipnResponse(PaypalIPN $paypalIPN)
  {
    return $paypalIPN->displayData();
  }

  public function postback()
  {
      return response()->json(['key' => file_get_contents('php://input')]);
  }

  public function callback(PaypalIPN $paypalIPN, Request $request)
  {
    // $content = file_get_contents('php://input');
    // Storage::put('ipn_log.txt', $content);
    // $paypalIPN->storeData();

    dd($paypalIPN->liveCallback($request));
    dd($paypalIPN->displayData('array'));
    dd($paypalIPN->fakeCallbackToPaypal());
  }

  /* Display avaiable products */
  public function productsView()
  {
    $products = Product::all();
    return view('products', ['products' => $products]);
  }
}
