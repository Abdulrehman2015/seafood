<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public string $mailLocale;

    public function __construct(
        public Order $order,
        ?string $mailLocale = null
    ) {
        $this->mailLocale = $mailLocale ?: current_locale() ?: ($order->user?->preferred_locale ?? 'en');
        $this->locale($this->mailLocale);
    }

    public function envelope(): Envelope
    {
        $orderNo = $this->order->order_number ?? '#' . $this->order->id;
        $appName = config('app.name', 'MST Import & Export');
        $subject = __t('email.order_confirmed_subject', 'Order Confirmed: :order — :app', [
            'order' => $orderNo,
            'app'   => $appName,
        ], $this->mailLocale);

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation',
            with: [
                'mailLocale' => $this->mailLocale,
            ]
        );
    }
}
