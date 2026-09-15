<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestSmtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $targetEmail,
        public ?string $mailHost = null,
        public ?string $fromAddress = null,
        public ?string $fromName = null,
        public ?string $mailMailer = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'SMTP Connection Test Confirmation — ' . config('app.name', 'MST Import & Export'));
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.test-smtp',
            with: [
                'targetEmail' => $this->targetEmail,
                'mailHost'    => $this->mailHost,
                'fromAddress' => $this->fromAddress,
                'fromName'    => $this->fromName,
                'mailMailer'  => $this->mailMailer,
            ]
        );
    }
}
