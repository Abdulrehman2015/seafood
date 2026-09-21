<?php

namespace App\Mail;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuotationReady extends Mailable
{
    use Queueable, SerializesModels;

    public string $mailLocale;

    public function __construct(
        public Quotation $quotation,
        ?string $mailLocale = null
    ) {
        $this->mailLocale = $mailLocale ?: ($quotation->user?->preferred_locale ?? current_locale());
        $this->locale($this->mailLocale);
    }

    public function envelope(): Envelope
    {
        $appName = config('app.name', 'MST Import & Export');
        $subject = __t('email.quotation_ready_subject', 'Quotation Ready: :quotation — :app', [
            'quotation' => $this->quotation->quotation_number,
            'app'       => $appName,
        ], $this->mailLocale);

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.quotation-ready',
            with: [
                'mailLocale' => $this->mailLocale,
            ]
        );
    }
}
