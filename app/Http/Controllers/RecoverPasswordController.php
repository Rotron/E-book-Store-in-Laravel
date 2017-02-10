<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\SendResetMail;

class RecoverPasswordController extends Controller
{
    public function RecoverPasswordView()
    {
        return view('recover-password');
    }

    public function recoverPassword(Request $request)
    {
        $this->validate($request, [
        'email' => 'required|email|exists:users',
        'username' => 'exists:users'
        ]);

        $this->username = $request->input('username');

        $this->email = $request->input('email');

        Mail::to($this->email)->send(new SendResetMail($this->username, $this->email));
    }

}
