<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActaMatrimonio extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'acta_matrimonio';
    protected $primaryKey = 'id_acta_matrimonio';
    protected $fillable = [
        'fecha_matrimonio',
        'fecha_registro',
        'regimen_matrimonial',
        'ruta_archivo_pdf',
        'estado',
        'id_folio',
        'id_alcalde',
        'dni_conyuge1',
        'dni_conyuge2',
        'id_usuario',
        'id_distrito_mat',
        'ruta_doc_generado'
    ];
    public function conyuge1()
    {
        return $this->belongsTo(Persona::class, 'dni_conyuge1', 'dni');
    }
    public function conyuge2()
    {
        return $this->belongsTo(Persona::class, 'dni_conyuge2', 'dni');
    }
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
    public function alcalde()
    {
        return $this->belongsTo(Alcalde::class, 'id_alcalde');
    }
    public function folio()
{
    return $this->belongsTo(Folio::class, 'id_folio', 'id_folio');
}

public function distrito()
{
    return $this->belongsTo(Distrito::class, 'id_distrito_mat', 'id_distrito');
}
}
