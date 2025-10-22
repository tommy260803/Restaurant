<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoProveedor extends Model
{
    protected $table = 'proveedor_documento';
    protected $primaryKey = 'idDocumento';
    public $timestamps = false;

    protected $fillable = [
        'idProveedor',
        'tipo',
        'archivo',
        'fecha_subida',
    ];

    // Relación: un documento pertenece a un proveedor
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'idProveedor', 'idProveedor');
    }
}