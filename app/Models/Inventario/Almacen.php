<?php
namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    protected $table = 'almacenes';
    protected $fillable = [
        'nombre', 'ubicacion', 'responsable'
    ];

    public function ingredientes()
    {
        return $this->belongsToMany(Ingrediente::class, 'almacen_ingredientes', 'almacen_id', 'ingrediente_id')
            ->withPivot('stock');
    }
}
