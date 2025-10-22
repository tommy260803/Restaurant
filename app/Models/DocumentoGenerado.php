<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoGenerado extends Model
{
    protected $table = 'documentos_generados';
    protected $primaryKey = 'id_documento_generados';
    public $timestamps = false;
    protected $fillable = [
        'id_persona',
        'tipo_acta',
        'ruta_pdf',
        'codigo_qr',
        'id_usuario',
        'fecha_emision',
    ];
}
