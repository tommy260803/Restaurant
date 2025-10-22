<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registrador extends Model
{
    protected $table = 'registrador';
    protected $primaryKey = 'id_registrador';
    public $timestamps = false;

    protected $fillable = [
        'estado',
        'id_usuario',
        'id_administrador',
    ];

    // Relación con el modelo Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    // Relación con el modelo Administrador (opcional)
    public function administrador()
    {
        return $this->belongsTo(Administrador::class, 'id_administrador');
    }

    public function persona()
    {
        return $this->hasOneThrough(
            Persona::class,    // Modelo final
            Usuario::class,    // Modelo intermedio
            'id_usuario',      // Clave foránea en la tabla intermedia (usuario)
            'dni',             // Clave foránea en la tabla final (persona)
            'id_usuario',      // Clave local en esta tabla (registrador)
            'dni_usuario'      // Clave local en la tabla intermedia (usuario)
        );
    }
}
