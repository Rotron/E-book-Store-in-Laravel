<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Mail;
use App\User;

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

  public function editUser(\App\Http\Requests\EditUser $request)
  {
    $username     = $request->input('username');
    $email        = $request->input('email');
    $password     = $request->input('password');
    $confirmed    = $request->input('confirmed');
    $userId       = $request->input('userId');

    $user = User::findOrFail($userId);
    $user->username = $username;
    $user->username = $email;

    $user->saveOrFail();
    Mail::to($request->input('email'), new SendConfirmationMail($username, $confirmationCode));
  }

  public function searchUser(\App\Http\Requests\SearchUser $request)
  {
    $queryResults = User::where('username', $request->input('query'))
    ->orWhere('email', $request->input('query'))->paginate();

    return view('admin/users', ['usersList' => $queryResults]);
  }

}
