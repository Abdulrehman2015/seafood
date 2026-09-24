<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public string $mailLocale;

    public function __construct(
        public User $user,
        ?string $mailLocale = null
    ) {
        $this->mailLocale = $mailLocale ?: current_locale() ?: ($user->preferred_locale ?? 'en');
        $this->locale($this->mailLocale);
    }

    public function envelope(): Envelope
    {
        $appName = config('app.name', 'MST Import & Export');
        $subject = $this->user->isPending()
            ? __t('email.user_registered_subject_pending', 'Account Application Received — :app', ['app' => $appName], $this->mailLocale)
            : __t('email.user_registered_subject_active', 'Welcome to :app!', ['app' => $appName], $this->mailLocale);

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user-registered',
            with: [
                'mailLocale' => $this->mailLocale,
            ]
        );
    }
}
