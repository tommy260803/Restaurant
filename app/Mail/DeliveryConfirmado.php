<?php

namespace App\Mail;

use App\Models\DeliveryPedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Log;

class DeliveryConfirmado extends Mailable
{
    use Queueable, SerializesModels;

    public $pedido;

    public function __construct(DeliveryPedido $pedido)
    {
        $this->pedido = $pedido;
    }

    public function build()
    {
        $mail = $this->subject('Pedido Delivery Confirmado')
            ->view('emails.delivery_confirmado')
            ->with('pedido', $this->pedido);

        // (opcional) PDF adjunto, igual que reservas
        try {
            $pdf = PDF::loadView('pdfs.delivery_comprobante', [
                'pedido' => $this->pedido
            ]);

            $mail->attachData(
                $pdf->output(),
                'delivery_' . $this->pedido->id . '.pdf',
                ['mime' => 'application/pdf']
            );
        } catch (\Exception $e) {
            Log::warning('No se pudo adjuntar PDF delivery: ' . $e->getMessage());
        }

        return $mail;
    }
}
