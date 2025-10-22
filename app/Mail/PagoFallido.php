<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PagoFallido extends Mailable
{
    use Queueable, SerializesModels;

    public $pago;

    /**
     * Create a new message instance.
     */
    public function __construct($pago)
    {
        $this->pago = $pago;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('❌ Notificación de pago fallido')
                    ->view('emails.pago_fallido');
    }
}
