<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewUserRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user)
    {
        $this->locale('en');
    }

    public function envelope(): Envelope
    {
        $subject = 'New ' . ucfirst($this->user->customer_group) . ' Registration: ' . $this->user->name . ' — ' . config('app.name', 'MST Import & Export');
        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-new-user',
            with: ['mailLocale' => 'en']
        );
    }
}
