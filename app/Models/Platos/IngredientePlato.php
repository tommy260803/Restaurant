<?php
namespace App\Models\Platos;

use Illuminate\Database\Eloquent\Model;

class IngredientePlato extends Model
{
    protected $table = 'ingredientes_platos';
    protected $fillable = [
        'plato_id', 'ingrediente_id', 'cantidad'
    ];

    public function plato()
    {
        return $this->belongsTo(Plato::class, 'plato_id');
    }

    public function ingrediente()
    {
        return $this->belongsTo(\App\Models\Inventario\Ingrediente::class, 'ingrediente_id');
    }
}
