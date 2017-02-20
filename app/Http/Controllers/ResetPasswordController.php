<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\SendResetMail;
use Hash;
use App\User;

class ResetPasswordController extends Controller
{
    // View to reset pass
    public function resetPasswordView()
    {
        return view('reset-password.reset-password-view');
    }

    // Send reset link to email if username matches with email
    // and saves token to database
    public function sendResetLink(Request $request)
    {
        $this->username     = $request->input('username');
        $this->email        = $request->input('email');
        $this->resetToken   = str_random(25);

        if (User::where(['username' => $this->username, 'email' => $this->email])->count() <= 0) {
            return back()->withInput()->withErrors('Sorry no matching credentials found with those details');
        }

        Mail::to($this->email)->send(new SendResetMail($this->username, $this->email, $this->resetToken));

        User::where('username', $this->username)->update(['recovery_token' => $this->resetToken]);

        return back()->with(['notice' => 'Confirmation mail sent']);
    }

    // Check if recovery_token exist for user trying to reset their pass
    public function matchToken($username, $resetToken)
    {
        return User::where(['username' => $username, 'recovery_token' => $resetToken])->exists();
    }

    // Set new password view
    public function setNewPasswordView($username, $resetToken)
    {
        $tokenMatch = $this->matchToken($username, $resetToken);

        if ($tokenMatch) {
            return view('reset-password.set-new-password-view', ['username' => $username, 'resetToken' => $resetToken]);
        }

        return redirect('/')->with(['notice' => 'Wrong token..']);
    }

    // Check submitted
    public function changePassword(Request $request)
    {
        $username = $request->input('username');
        $resetToken = $request->input('reset_token');
        $password  = $request->input('password');


        $tokenMatch = $this->matchToken($username, $resetToken);

        if (!$tokenMatch) {
            return redirect('/')->with(['notice' => 'Wrong token']);
        }

        $this->validate($request, [
            'password' => 'required|min:10',
        ]);

        $updatePassword = User::where('username', $username)->update(['password' => Hash::make($password), 'recovery_token' => null]);

        if($updatePassword > 0) {
            return redirect('/')->with(['notice' => 'Password has been changed']);
        }

        throw new \Exception('Password couldnt be updated');
    }


}
