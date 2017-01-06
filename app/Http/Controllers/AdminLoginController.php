<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
  public function adminLoginView()
  {
    return view('admin/login');
  }

  public function login(Request $request)
  {
    $this->validate($request, [
      'username' => 'required',
      'password' => 'required',
    ]);

    $username = $request->input('username');
    $password = $request->input('password');

    if (Auth::attempt(['username' => $username, 'password' => $password])) {
      return redirect()->intended('admin/admincp');
    }
    return redirect('admin')->with(['loginFailed' => 'Wrong user or pass']);
  }

  public function logout()
  {
    Auth::logout();
    return redirect('admin')->with(['loggedOut' => 'You have been logged out']);
  }

  public function admincp()
  {
    return view('admin/admincp');
  }

}
