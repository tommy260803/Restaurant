<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reserva;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Log;

class ReservaConfirmada extends Mailable
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
        $mail = $this->subject('Confirmación de reserva')
                    ->view('emails.reserva_confirmada')
                    ->with('reserva', $this->reserva);

        // Intentar generar PDF y adjuntarlo
        try {
            $pdf = PDF::loadView('pdfs.reserva_comprobante', ['reserva' => $this->reserva]);
            $pdfData = $pdf->output();
            $mail->attachData($pdfData, 'reserva_' . $this->reserva->id . '.pdf', [
                'mime' => 'application/pdf'
            ]);
        } catch (\Exception $e) {
            Log::warning('No se pudo generar o adjuntar PDF para reserva ' . $this->reserva->id . ': ' . $e->getMessage());
        }

        return $mail;
    }
}
