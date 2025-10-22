<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
    use HasFactory;

    // Ajusta estos valores si tu tabla/PK tienen otro nombre
    protected $table = 'platos_productos';    // e.g. 'platos', 'platos_productos'
    protected $primaryKey = 'idPlatoProducto';// e.g. 'idPlato' o 'idPlatoProducto'
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'disponible',
        'idCategoria', // FK hacia categoria (ajusta si usas 'categoria_id' u otro)
    ];

    /**
     * Relación: un plato pertenece a una categoría
     * Se asume FK 'idCategoria' en la tabla de platos.
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'idCategoria', 'idCategoria');
    }

    /**
     * Parte Harry
     */
    public function ingredientes()                                                                         
    {
        return $this->belongsToMany(\App\Models\Inventario\Ingrediente::class, 'ingredientes_platos', 'idPlatoProducto', 'ingrediente_id')
            ->withPivot('cantidad');
    }

    public function calcularCosto()
    {
        $costo = 0;
        foreach ($this->ingredientes as $ingrediente) {
            $costo += $ingrediente->pivot->cantidad * $ingrediente->costo_promedio;
        }
        return $costo;
    }
}
