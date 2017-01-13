<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChangeAdminPassword extends Controller
{

  // Send guests to index. Admin must be logged in to change pass
  public function __construct()
  {
    $this->middleware('redirectGuest');
  }


  // Return change password form
  public function changePasswordView()
  {
    return view('admin/change-password-view');
  }


  // Validate request and change password
  public function changePassword()
  {
    $this->validate($request, [
      'adminUsername' => 'required',
      'adminPassword' => 'required|min:10'
    ]);

    $user = User::find(1);

    $user->password = $request->input('adminPassword');

    $user->saveOrFail();

    if ($user->save()) {
      Auth::logout();
      return redirect('admin')->with(['passChanged' => 'You have been logged out. Login with new password']);
    }

    return back()->with(['passNotChanged' => 'Password could not be changed']);
  }

}
