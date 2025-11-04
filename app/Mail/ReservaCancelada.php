<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reserva;

class ReservaCancelada extends Mailable
{
    use Queueable, SerializesModels;

    public $reserva;

    /**
     * Create a new message instance.
     */
    public function __construct(Reserva $reserva)
    {
        $this->reserva = $reserva;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Notificación de pago fallido - Reserva')
                    ->view('emails.reserva_cancelada')
                    ->with('reserva', $this->reserva);
    }
}
