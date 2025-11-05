<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'reserva_platos';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'reserva_id',
        'plato_id',
        'cantidad',
        'precio',
        'estado', // Enviado a cocina | En preparación | Preparado
        'notas',
        'observaciones',
        'en_preparacion_at',
        'preparado_at',
    ];

    protected $casts = [
        'en_preparacion_at' => 'datetime',
        'preparado_at' => 'datetime',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'reserva_id', 'id');
    }

    public function plato()
    {
        return $this->belongsTo(Plato::class, 'plato_id', 'idPlatoProducto');
    }
}
