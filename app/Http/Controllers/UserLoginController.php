<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends Controller
{
  public function loginView()
  {
    return view('login');
  }

  // Validate request and login
  public function login(Request $request)
  {
    $this->validate($request, [
      'username' => 'required',
      'password' => 'required',
    ]);

    $username = $request->input('username');
    $password = $request->input('password');
    $remember = $request->input('remember');

    if (Auth::attempt(['username' => $username, 'password' => $password], $remember)) {
      return redirect()->intended('/');
    }

    return back()->with(['loginFailed' => 'Wrong user or pass']);
  }

  // Logout and redirect back with message
  public function logout()
  {
    Auth::logout();
    return redirect('/')->with(['loggedOut' => 'You have been logged out']);
  }

}
