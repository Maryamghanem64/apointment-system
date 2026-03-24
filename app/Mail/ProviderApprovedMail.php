<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ProviderApprovedMail extends Mailable
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Welcome to Schedora — Your Provider Account is Ready!'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.provider_approved'
        );
    }
}
