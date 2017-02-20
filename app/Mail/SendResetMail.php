<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class SendResetMail extends Mailable
{
    public $resetToken;
    public $username;
    public $email;

    public function __construct($username, $email, $resetToken)
    {
        $this->resetToken = $resetToken;
        $this->username = $username;
        $this->email = $email;
    }

    public function build()
    {
        return $this->view('reset-password.reset-password-mail-template');
    }
}
