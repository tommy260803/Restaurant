<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    protected $keyType = 'int';
    public $timestamps = false;
    
    protected $fillable = [
        'dni_usuario',
        'nombre_usuario',
        'contrasena',
        'email_mi_acta',
        'email_respaldo',
        'rol',
        'estado',
        'foto',
        'portada',
    ];

    protected $hidden = [
        'contrasena',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'dni_usuario', 'dni');
    }

    public function administrador()
    {
        return $this->hasOne(Administrador::class, 'id_usuario', 'id_usuario');
    }

    public function registrador()
    {
        return $this->hasOne(Registrador::class, 'id_usuario', 'id_usuario');
    }

    public function getAuthIdentifierName()
    {
        return 'id_usuario';
    }

    public function getAuthIdentifier()
    {
        return $this->id_usuario;
    }

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function getEmailForPasswordReset()
    {
        return $this->email_mi_acta;
    }

    public function getNombreCompletoAttribute()
    {
        return $this->persona ? $this->persona->nombre_completo : ($this->nombre_usuario ?? 'Usuario sin nombre');
    }
}