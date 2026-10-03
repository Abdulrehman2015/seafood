<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderScheduleNotification extends Mailable
{
    use Queueable, SerializesModels;

    public string $mailLocale;
    public ?string $customMessage;

    public function __construct(
        public Order $order,
        ?string $customMessage = null,
        ?string $mailLocale = null
    ) {
        $this->customMessage = $customMessage;
        $this->mailLocale = $mailLocale ?: current_locale() ?: ($order->user?->preferred_locale ?? 'en');
        $this->locale($this->mailLocale);
    }

    public function envelope(): Envelope
    {
        $orderNo = $this->order->order_number ?? '#' . $this->order->id;
        $appName = config('app.name', 'MST Import and Export Sdn. Bhd.');
        $isWalkin = $this->order->isWalkin();

        if ($isWalkin) {
            $subject = __t('email.collection_updated_subject', 'Your Self-Collection Schedule Date is Updated: Order :order — :app', [
                'order' => $orderNo,
                'date'  => $this->order->confirmed_date ?: $this->order->collection_date ?: date('d M Y'),
                'app'   => $appName,
            ], $this->mailLocale);
        } else {
            $subject = __t('email.delivery_updated_subject', 'Your Delivery Schedule Date is Updated: Order :order — :app', [
                'order' => $orderNo,
                'date'  => $this->order->confirmed_date ?: $this->order->delivery_date ?: date('d M Y'),
                'app'   => $appName,
            ], $this->mailLocale);
        }

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-schedule-notification',
            with: [
                'order'         => $this->order,
                'customMessage' => $this->customMessage,
                'mailLocale'    => $this->mailLocale,
                'isWalkin'      => $this->order->isWalkin(),
            ]
        );
    }
}
