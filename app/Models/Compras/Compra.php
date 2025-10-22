<?php
namespace App\Models\Compras;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compra';
    protected $primaryKey = 'idCompra';
    protected $fillable = [
        'idProveedor', 'fecha', 'descripcion', 'total', 'estado'
    ];

    public function proveedor()
    {
        return $this->belongsTo(\App\Models\Proveedor::class, 'idProveedor');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class, 'idCompra');
    }
}
