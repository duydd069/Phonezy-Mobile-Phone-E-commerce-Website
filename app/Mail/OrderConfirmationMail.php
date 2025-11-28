<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $verificationUrl = route('client.order.verify', ['token' => $this->order->verification_token]);

        return $this->subject('Xác nhận đơn hàng #' . $this->order->id)
            ->view('emails.order-confirmation', [
                'order' => $this->order,
                'verificationUrl' => $verificationUrl,
            ]);
    }
}

