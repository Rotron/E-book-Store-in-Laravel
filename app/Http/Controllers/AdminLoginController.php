<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
  public function adminLoginView()
  {

  }

  public function adminLogin()
  {
    if (Auth::attempt(['username' => 'admin', 'password' => 'admin'])) {
      Auth::intended('admincp');
    }
    return 'failed';
  }

  public function admincp()
  {

  }
}
