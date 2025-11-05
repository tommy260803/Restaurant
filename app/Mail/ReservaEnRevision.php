<?php

namespace App\Mail;

use App\Models\Reserva;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservaEnRevision extends Mailable
{
    use Queueable, SerializesModels;

    public Reserva $reserva;

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
        return $this->subject('Tu reserva está en revisión')
            ->view('emails.reservas.en_revision')
            ->with([
                'reserva' => $this->reserva,
            ]);
    }
}
