<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmailOtp extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $otp
    ) {}

    public function envelope(): Envelope
    {
        $appName = config('app.name', 'MST Seafood');
        return new Envelope(
            subject: "[{$this->otp}] Your Account Verification Code — {$appName}"
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.verify-otp');
    }
}
