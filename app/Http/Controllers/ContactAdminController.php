<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Contact;

class ContactAdminController extends Controller
{


  public function contactView()
  {
    // dd(get_class($request));
    // return view('contact');
  }

  public function validateContactForm()
  {
    $responseToken = $request->input('g-recaptcha-response');

    /* Verify site */
    $newConn = new Recaptcha('6LcNyg8UAAAAAKnxP5X6MBlmIO98K0QvtS6P95Kb');

    /* Verify form response token with google servers */
    $verifyResponse = $newConn->verify($responseToken, $_SERVER['REMOTE_ADDR']);

    /* Check if was success */
    if ($verifyResponse->isSuccess()) {
      return true;
    }

    return false;
  }

  public function sendMail(Request $request)
  {

    $name     = $request->input('name');
    $email    = $request->input('email');
    $message  = $request->input('message');

    if ($this->validateContactForm()) {
      Mail::to ( config('mail.from.address') )
        ->send( new ContactAdmin( $name, $email, $message) );
        return redirect('contact')->with(['notice' => 'Message sent, will reply ASAP!']);
    }

    return redirect('contact')->withInput()->withErrors(['error' => 'Please re fill captcha']);

  }

}
