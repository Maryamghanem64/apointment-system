<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewProviderApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fullName;
    public $email;

    public function __construct($fullName, $email)
    {
        $this->fullName = $fullName;
        $this->email = $email;
    }

    public function build()
    {
        return $this->subject('New Provider Application')
                    ->markdown('emails.provider-application-new');
    }
}

