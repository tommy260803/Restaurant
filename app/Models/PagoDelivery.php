<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoDelivery extends Model
{
    use HasFactory;

    protected $table = 'pagos_delivery';

    protected $fillable = [
        'delivery_id',
        'metodo',
        'numero_operacion',
        'monto',
        'estado',
    ];

    public $timestamps = false;

    // 🔗 Pedido delivery
    public function delivery()
    {
        return $this->belongsTo(DeliveryPedido::class, 'delivery_id');
    }
}
