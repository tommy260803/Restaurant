<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compra';
    protected $primaryKey = 'idCompra';
    public $timestamps = true;

    protected $fillable = [
        'idProveedor',
        'fecha',
        'descripcion',
        'total',
        'estado',
    ];

    // Relación: una compra pertenece a un proveedor
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'idProveedor', 'idProveedor');
    }

    // Relación: una compra puede tener muchos detalles (opcional)
    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class, 'idCompra', 'idCompra');
    }
}