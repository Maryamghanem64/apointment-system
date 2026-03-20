<?php

namespace App\Mail;

use App\Models\ProviderApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProviderApplicationApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public function __construct(ProviderApplication $application)
    {
        $this->application = $application;
    }

    public function build()
    {
        return $this->subject('Your Provider Application Has Been Approved')
                    ->markdown('emails.provider-application-approved');
    }
}

