<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';
    public $timestamps = false;

    protected $fillable = [
        'DNI', 
        'Correo',
        'monto', 
        'tipo_acta', 
        'id_acta',        
        'estado',
        'metodo_pago', 
        'num_transaccion', 
        'fecha_pago',
    ];
}