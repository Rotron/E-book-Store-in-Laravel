<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($name, $email, $message)
    {
      $this->name     = $name;
      $this->email    = $email;
      $this->message  = $message;
    }

    public function build()
    {
        return $this->view('contact-mail-template')
        ->replyTo($this->email, $this->name)
        ->subject(substr($this->message, 0, 50));
    }
}
