<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $customer,
        public array $cart,
        public float $total,
        public bool $isCustomerCopy = false,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->isCustomerCopy ? 'Your order was received' : 'New order received'
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.new-order');
    }
}

