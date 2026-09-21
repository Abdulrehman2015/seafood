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

    public string $mailLocale;

    public function __construct(
        public User $user,
        public string $otp,
        ?string $mailLocale = null
    ) {
        $this->mailLocale = $mailLocale ?: ($user->preferred_locale ?? current_locale());
        $this->locale($this->mailLocale);
    }

    public function envelope(): Envelope
    {
        $appName = config('app.name', 'MST Seafood');
        $subject = __t('email.otp_subject', '[:otp] Your Account Verification Code — :app', [
            'otp' => $this->otp,
            'app' => $appName,
        ], $this->mailLocale);

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.verify-otp',
            with: [
                'mailLocale' => $this->mailLocale,
            ]
        );
    }
}
