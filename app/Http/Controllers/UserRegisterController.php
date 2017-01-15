<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserRegisterController extends Controller
{

  public function registerView(){
    return view('user-register');
  }

  public function register()
  {
    $username = $request->input('username');
    $password = $request->input('password');
    $email = $request->input('email');

    $this->validate($request, [
      'username' => 'required, unique:users, username',
      'password' => 'required|min:10',
      'email' => 'requried, email, unique:users, email',
    ]);

    $user = new User;
    $user->username = $username;
    $user->password = $password;
    $user->email = $email;
    $user->role = 2;
  }

}
