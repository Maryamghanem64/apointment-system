<?php

namespace App\Mail;

use App\Models\ProviderApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProviderApplicationRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public function __construct(ProviderApplication $application)
    {
        $this->application = $application;
    }

    public function build()
    {
        return $this->subject('Provider Application Update')
                    ->markdown('emails.provider-application-rejected');
    }
}

