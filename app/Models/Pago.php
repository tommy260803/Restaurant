<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    // La tabla usa 'id' como PK
    protected $primaryKey = 'id';
    public $timestamps = false;

    // Columnas reales en la tabla: id, venta_id, reserva_id, metodo, numero_operacion, monto, fecha, estado
    protected $fillable = [
        'venta_id',
        'reserva_id',
        'metodo',
        'numero_operacion',
        'monto',
        'fecha',
        'estado',
    ];

    // Relación con reserva (si aplica)
    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'reserva_id', 'id');
    }
}