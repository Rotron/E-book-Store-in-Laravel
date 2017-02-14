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

  public function __construct($username)
  {
    $this->username = $username;
    $this->confirmationCode = str_random(20);
  }

  public function build()
  {
    return $this->view('layouts.confirmation-mail');
  }
}
