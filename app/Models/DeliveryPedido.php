<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryPedido extends Model
{
    use HasFactory;

    protected $table = 'delivery_pedidos';

    protected $fillable = [
        'idCliente',
        'nombre_cliente',
        'email',
        'telefono',
        'direccion_entrega',
        'referencia',
        'fecha_pedido',
        'hora_pedido',
        'comentarios',
        'estado',
    ];

    // 🔗 Platos del pedido
    public function platos()
    {
        return $this->hasMany(DeliveryPedidoPlato::class, 'delivery_pedido_id');
    }

    // 🔗 Pago del delivery
    public function pago()
    {
        return $this->hasOne(PagoDelivery::class, 'delivery_id');
    }
}
