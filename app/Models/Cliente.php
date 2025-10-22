<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente'; 
    protected $primaryKey = 'idCliente';
    public $timestamps = true; 

    protected $fillable = [
        'nombre',
        'apellidoPaterno',
        'apellidoMaterno',
        'telefono',
        'email',
        'puntos',
        'preferencias',
        'estado',
    ];

    
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellidoPaterno} {$this->apellidoMaterno}";
    }

    public static function obtenerTodos()
    {
        return self::all();
    }
}
