<?php
namespace App\Models\Compras;

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
        'cantidad_recibida',
        'precio_unitario',
        // 'subtotal' NO va aquí - es columna generada por MySQL
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'idCompra');
    }

    public function ingrediente()
    {
        return $this->belongsTo(\App\Models\Inventario\Ingrediente::class, 'idIngrediente', 'id');
    }

    public function lotes()
    {
        return $this->hasMany(\App\Models\Inventario\IngredienteLote::class, 'idDetalleCompra');
    }
}
