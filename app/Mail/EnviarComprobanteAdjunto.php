<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnviarComprobanteAdjunto extends Mailable
{
    use Queueable, SerializesModels;

    public $pago;
    public $ruta;

    /**
     * Create a new message instance.
     */
    public function __construct($pago, $ruta)
    {
        $this->pago = $pago;
        $this->ruta = $ruta;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('emails.comprobante') // debes crear esta vista
                    ->subject('Comprobante de Pago')
                    ->attach(storage_path("app/public/{$this->ruta}"));
    }
}
