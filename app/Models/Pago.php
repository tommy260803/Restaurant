<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    // La tabla usa 'id' como PK
    protected $primaryKey = 'id';
    public $timestamps = false;

    // Columnas reales en la tabla: id, cliente_id, orden_id, venta_id, reserva_id, metodo, numero_operacion, monto, fecha, estado
    protected $fillable = [
        'cliente_id',
        'orden_id',
        'venta_id',
        'reserva_id',
        'metodo',
        'numero_operacion',
        'monto',
        'fecha',
        'estado',
    ];

    // Relación con cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'idCliente');
    }

    // Relación con orden
    public function orden()
    {
        return $this->belongsTo(Orden::class, 'orden_id', 'id');
    }

    // Relación con reserva (si aplica)
    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'reserva_id', 'id');
    }
}