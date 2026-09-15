<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        $orderNo = $this->order->order_number ?? '#' . $this->order->id;
        $amount = number_format($this->order->total ?? 0, 2);
        return new Envelope(subject: 'New Order Received: ' . $orderNo . ' (RM ' . $amount . ') — ' . config('app.name', 'MST Import & Export'));
    }

    public function content(): Content
    {
        return new Content(view: 'emails.admin-new-order');
    }
}
