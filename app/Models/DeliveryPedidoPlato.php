<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryPedidoPlato extends Model
{
    use HasFactory;

    protected $table = 'delivery_pedido_platos';

    protected $fillable = [
        'delivery_pedido_id',
        'plato_id',
        'cantidad',
        'precio',
        'notas',
        'estado',
        'observaciones',
        'en_preparacion_at',
        'preparado_at',
    ];

    public $timestamps = true;

    // 🔗 Pedido delivery
    public function pedido()
    {
        return $this->belongsTo(DeliveryPedido::class, 'delivery_pedido_id');
    }

    // 🔗 Plato
    public function plato()
    {
        return $this->belongsTo(Plato::class, 'plato_id');
    }
}
