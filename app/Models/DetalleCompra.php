<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $table = 'detalle_compra';
    protected $primaryKey = 'idDetalleCompra';
    public $timestamps = false;

    protected $fillable = [
        'idCompra',
        'idIngrediente',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    // Relación: un detalle pertenece a una compra
    public function compra()
    {
        return $this->belongsTo(Compra::class, 'idCompra', 'idCompra');
    }

    // Relación: un detalle pertenece a un ingrediente
    // public function ingrediente()
    // {
    //     return $this->belongsTo(Ingrediente::class, 'ingrediente_id', 'id');
    // }
}
