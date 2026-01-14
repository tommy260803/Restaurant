<?php

namespace App\Mail;

use App\Models\DeliveryPedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PedidoEnRevision extends Mailable
{
    use Queueable, SerializesModels;

    public $pedido;

    public function __construct(DeliveryPedido $pedido)
    {
        $this->pedido = $pedido;
    }

    public function build()
    {
        return $this->subject('Tu pedido está en revisión')
            ->view('emails.delivery_en_revision')
            ->with('pedido', $this->pedido);
    }
}
