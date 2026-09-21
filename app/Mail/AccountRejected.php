<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountRejected extends Mailable
{
    use Queueable, SerializesModels;

    public string $mailLocale;

    public function __construct(
        public User $user,
        ?string $mailLocale = null
    ) {
        $this->mailLocale = $mailLocale ?: ($user->preferred_locale ?? current_locale());
        $this->locale($this->mailLocale);
    }

    public function envelope(): Envelope
    {
        $appName = config('app.name', 'MST Import & Export');
        $subject = __t('email.account_rejected_subject', 'Update on Your Account Application — :app', [
            'app' => $appName,
        ], $this->mailLocale);

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.account-rejected',
            with: [
                'mailLocale' => $this->mailLocale,
            ]
        );
    }
}
