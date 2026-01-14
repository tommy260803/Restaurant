<?php
namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;

class Ingrediente extends Model
{
    protected $table = 'ingredientes';
     protected $primaryKey = 'id'; // ✅ Cambiar de 'idIngrediente' a 'id'
    public $incrementing = true;
    protected $keyType = 'int';
    // La tabla usa 'id' como primary key por defecto
    protected $fillable = [
        'nombre', 'unidad', 'stock', 'stock_minimo', 'costo_promedio', 'estado'
    ];

    public function detallesCompra()
    {
        return $this->hasMany(\App\Models\Compras\DetalleCompra::class, 'idIngrediente', 'id');
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class, 'ingrediente_id');
    }

    public function lotes()
    {
        return $this->hasMany(IngredienteLote::class, 'ingrediente_id');
    }

    public function almacenes()
    {
        return $this->belongsToMany(Almacen::class, 'almacen_ingredientes', 'ingrediente_id', 'almacen_id')
            ->withPivot('stock');
    }

    public function platos()
    {
        return $this->belongsToMany(\App\Models\Plato::class, 'ingredientes_platos', 'ingrediente_id', 'plato_id')
            ->withPivot('cantidad');
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }
}
