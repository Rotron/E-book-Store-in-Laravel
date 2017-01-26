<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SendConfirmationMail;
use Mail;
use App\User;
use App\Http\Requests\EditUser;

class ManageUserController extends Controller
{
  public function usersView()
  {
    return view('admin/users', ['usersList' => User::paginate(10)]);
  }

  public function userEditView($id)
  {
    return view('admin/edit-user', ['user' => User::find(1)->first()]);
  }

  public function editUser(User $user, EditUser $request)
  {


    $username     = $request->input('username');
    $email        = $request->input('email');
    $password     = $request->input('password');
    $confirmed    = $request->input('confirmed');
    $userId       = $request->input('userId');
    $confirmationCode = str_random(32);

    $user->username   = $username;
    $user->email      = $email;
    // Only update pass if it exists and is atleast 10 characters long
    if (!is_null($password) && strlen($password) >= 10) {
      $user->password = $password;
    }

    // Set confirmation code to null if yes is checked
    if ($confirmed == 'yes') {
      $user->confirmation_code = null;
    }

    // Set new confirmation code if admin checks 'no', and user is not confirmed
    // Then send email to user with new confirmation code
    if($confirmed == 'no' && $user->confirmation_code == null) {
      $user->confirmation_code = $confirmationCode;
      $user->saveOrFail();
      return redirect()->back()->with(['notice' => 'You have deactivated this account. New confirmation code has been set. You can send the confirmation using Confirmation page']);
    }

    $user->saveOrFail();

    return back()->with(['notice' => 'Details updated']);
  }

  public function searchUser(\App\Http\Requests\SearchUser $request)
  {
    $queryResults = User::where('username', $request->input('query'))
    ->orWhere('email', $request->input('query'))->paginate();

    return view('admin/users', ['usersList' => $queryResults]);
  }

}
