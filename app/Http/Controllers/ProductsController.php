<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\Storage;
use \GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Paypal;

class ProductsController extends Controller
{

  public function __construct()
  {
    $this->paypal = new Paypal('http://localhost/listener');
  }

  public function index()
  {
    return view('index', ['products' => Product::paginate(10)]);
  }

  public function listener()
  {
    $this->paypal->storeData();
    dd($this->paypal->storeData());
  }

  public function product($slug = '', $id)
  {
    $product = Product::find($id);
    return view('product', ['product' => $product]);
  }

  /* Display avaiable products */
  public function productsView()
  {
    $products = Product::all();
    return view('products', ['products' => $products]);
  }
}
