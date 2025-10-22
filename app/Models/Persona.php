<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Distrito;
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
        'nacionalidad',
        'id_distrito',
        'estado_civil',
        'fecha_nacimiento',
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
    
    // Relación con Distrito
    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'id_distrito', 'id_distrito');
    }
    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'dni_usuario', 'dni');
    }
    public function alcalde()
    {
        return $this->hasOne(Alcalde::class, 'dni_alcalde', 'dni');
    }
    
}
