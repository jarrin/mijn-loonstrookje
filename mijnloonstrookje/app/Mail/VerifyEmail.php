<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class VerifyEmail extends Mailable
{
    public $verificationUrl;
    public $userName;

    public function __construct($verificationUrl, $userName = null)
    {
        $this->verificationUrl = $verificationUrl;
        $this->userName = $userName;
    }

    public function build()
    {
        return $this->subject('Bevestig je e-mailadres')
            ->view('mails.confirm-mail')
            ->with([
                'verificationUrl' => $this->verificationUrl,
                'userName' => $this->userName,
            ]);
    }
}
