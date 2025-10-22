<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    protected $table = 'tarifas';
    protected $primaryKey = 'id_tarifa';

    protected $fillable = [
        'tipo_acta',
        'monto',
        'vigente_desde',
        'vigente_hasta',
    ];

    public $timestamps = false; 
}
