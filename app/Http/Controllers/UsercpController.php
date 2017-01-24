<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsercpController extends Controller
{
  public function __construct()
  {
    $this->middleware('redirectGuest');
  }

  public function index()
  {
    return view('usercp');
  }


}
