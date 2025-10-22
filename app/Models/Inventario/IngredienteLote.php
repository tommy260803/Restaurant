<?php
namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;

class IngredienteLote extends Model
{
    protected $table = 'ingrediente_lotes';
    protected $fillable = [
        'ingrediente_id', 'lote', 'fecha_vencimiento', 'cantidad', 'idDetalleCompra'
    ];

    public function ingrediente()
    {
        return $this->belongsTo(Ingrediente::class, 'ingrediente_id');
    }

    public function detalleCompra()
    {
        return $this->belongsTo(\App\Models\Compras\DetalleCompra::class, 'idDetalleCompra');
    }
}
