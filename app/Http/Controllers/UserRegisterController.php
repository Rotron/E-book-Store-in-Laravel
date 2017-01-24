<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SendConfirmationMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Registration;
use \ReCaptcha\ReCaptcha;
use App\User;
use Hash;

class UserRegisterController extends Controller
{

  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  public function registerView(){
    return view('register');
  }

  public function checkCaptcha()
  {
    $recap = new reCaptcha(config('app.gcaptcha_secret'));
    $remoteIp = $_SERVER['REMOTE_ADDR'];

    $response = $recap->verify($this->request->input('g-recaptcha-response'), $remoteIp);

    if ($response->isSuccess()) {
      return true;
    }
    return false;
  }


  public function register(Registration $request)
  {
    if ($this->checkCaptcha()) {
      $username         = $this->request->input('username');
      $email            = $this->request->input('email');
      $password         = $this->request->input('password');
      $confirmationCode = str_random(32);

      $user                     = new User;
      $user->username           = $username;
      $user->password           = Hash::make($password);
      $user->email              = $email;
      $user->role               = 2;
      $user->confirmation_code  = $confirmationCode;
      $user->saveOrFail();

      if( Mail::to($email)->send(new sendConfirmationMail($username, $confirmationCode))) {
        return redirect()->back()->with(['notice' => 'Account ' . $username . 'has been registered. Please confirm']);
      }
      throw new \Exception('Failed to send confirmation mail');
    }
    return back()->withInput()->withErrors('Please re fill the captcha');
  }

  public function confirm($username, $confirmationCode)
  {
    User::where(['username' => $username, 'confirmation_code' => $confirmationCode])->firstOrFail();

    $user = User::where('username', $username)->firstOrFail();
    $user->confirmation_code = null;
    $user->saveOrFail();

    return redirect('/user/login')->with(['notice' => 'Your account has been confirmed']);
  }

}
