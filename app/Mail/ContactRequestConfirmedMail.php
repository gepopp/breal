<?php

namespace App\Mail;

use App\Models\ContactRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactRequestConfirmedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public ContactRequest $contactRequest)
    {
        $this->locale($contactRequest->locale ?? config('app.default_locale'));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('mail.confirmed.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email.contact-request-confirmed',
            with: [
                'contactRequest' => $this->contactRequest,
            ],
        );
    }
}
