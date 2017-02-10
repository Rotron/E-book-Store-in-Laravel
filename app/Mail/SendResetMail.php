<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class SendResetMail extends Mailable
{
    public function __construct($username, $email)
    {
        $this->resetCode = str_random(20);
        $this->username = $username;
        $this->email = $email;
    }

    public function build()
    {
        // Get this working.. send email to use with reset link.. create view
        return $this->view('sendResetMailView');
    }
}
