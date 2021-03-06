<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Login;
use App\User;

class UserLoginController extends Controller
{
  public function loginView()
  {
    return view('login');
  }

  // Validate request and login
  public function login(Login $request)
  {
    $username = $request->input('username');
    $password = $request->input('password');
    $remember = $request->input('remember');

    if(Auth::attempt(['username' => $username, 'password' => $password], $remember)) {

      if (is_null(User::where('username', $username)->first()->confirmation_code)) {
        return redirect()->intended('/');
      }
      return back()->withErrors('Confirm account please');
    }
    return back()->withErrors('Wrong user or pass');
  }

  // Logout and redirect back with message
  public function logout()
  {
    Auth::logout();
    return redirect('/')->with(['notice' => 'You have been logged out']);
  }

}
