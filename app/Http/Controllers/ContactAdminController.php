<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Recaptcha\Recaptcha;

class ContactAdminController extends Controller
{

  public function contactView()
  {
    return view('contact');
  }

  public function validateAdminContact(\App\Http\Requests\ContactRequest $request)
  {
    $responseToken = $request->input('g-recaptcha-response');

    $newConn = new Recaptcha('6LcNyg8UAAAAAKnxP5X6MBlmIO98K0QvtS6P95Kb');

    $verifyResponse = $newConn->verify($responseToken, $_SERVER['REMOTE_ADDR']);

    if ($verifyResponse->isSuccess()) {
      $this->sendMail();
    }
    return redirect('contact')->withInput()->withErrors(['error' => 'Please re fill captcha']);
  }

  public function sendMail(Request $request)
  {
    $name     = $request->input('name');
    $email    = $request->input('email');
    $message  = $request->input('message');
    Mail::to ( config('mail.from.address') )
      ->send( new ContactAdmin( $name, $email, $message) );

    return redirect('contact')->with(['notice' => 'Message sent, will reply ASAP!']);
  }

}
