<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnviarComprobanteAdjunto extends Mailable
{
    use Queueable, SerializesModels;

    public $pago;
    public $url_pdf;
    protected $ruta_pdf;

    public function __construct($pago, $ruta_pdf)
    {
        $this->pago = $pago;
        $this->ruta_pdf = $ruta_pdf;
        $this->url_pdf = asset('storage/' . $ruta_pdf);
    }

    public function build()
    {
        return $this->subject('Comprobante de Pago - MiActa')
                    ->view('emails.comprobante_pago')
                    ->attach(storage_path('app/public/' . $this->ruta_pdf), [
                        'as' => 'comprobante_pago.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}
