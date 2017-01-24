<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendConfirmationMail extends Mailable
{
  use Queueable, SerializesModels;
  
  public $username;
  public $confirmationCode;

  public function __construct($username, $confirmationCode)
  {
    $this->username = $username;
    $this->confirmationCode = $confirmationCode;
  }

  public function build()
  {
    return $this->view('confirmation-mail');
  }
}
