<?php

namespace App\Mail;

use App\Models\ContactRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class VerificationEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $verificationLink = '';

    /**
     * Create a new message instance.
     */
    public function __construct(?ContactRequest $contactRequest = null)
    {
        if (!is_null($contactRequest)) {
            $this->verificationLink = URL::signedRoute(
                'confirm',
                [
                    'id' => $contactRequest->id,
                    'token'   => $contactRequest->token,
                ]
            );
        }

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bestätigen Sie Ihre E-Mail-Adresse',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.email-verification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
