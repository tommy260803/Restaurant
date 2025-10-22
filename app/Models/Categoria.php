<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    // Ajusta estos valores si tu tabla/PK tienen otro nombre
    protected $table = 'categorias';         // e.g. 'categoria' o 'categorias'
    protected $primaryKey = 'idCategoria';  // e.g. 'idCategoria'
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    /**
     * Relación: una categoría tiene muchos platos
     * Asumimos que la FK en la tabla platos se llama 'idCategoria'
     */
    public function platos()
    {
        return $this->hasMany(Plato::class, 'idCategoria', 'idCategoria');
    }
}
