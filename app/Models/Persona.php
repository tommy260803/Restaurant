<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Usuario;

class Persona extends Model
{
    use HasFactory;
    protected $table = 'persona';
    public $timestamps = false; 
    protected $primaryKey = 'id_persona';
    protected $fillable = [
        'dni',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'sexo',
        'estado',
        'direccion',
    ];
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombres} {$this->apellido_paterno} {$this->apellido_materno}";
    }

    // Método para obtener datos básicos
    public static function obtenerTodas()
    {
        return self::select('id_persona', 'dni', 'nombres', 'apellido_paterno', 'apellido_materno')->get();
    }
    
    
    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'dni_usuario', 'dni');
    }  
}
