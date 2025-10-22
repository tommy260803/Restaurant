<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Administrador extends Model
{
    use HasFactory;

    protected $table = 'administrador';
    protected $primaryKey = 'id_administrador';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'estado',
    ];


    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function registradores()
    {
        return $this->hasOne(Registrador::class, 'id_administrador');
    }

}
