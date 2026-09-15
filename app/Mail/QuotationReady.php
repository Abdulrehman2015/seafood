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

    public function __construct(public Quotation $quotation) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Quotation Ready: ' . $this->quotation->quotation_number . ' — ' . config('app.name'));
    }

    public function content(): Content
    {
        return new Content(view: 'emails.quotation-ready');
    }
}
