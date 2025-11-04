<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    use HasFactory;

    protected $table = 'mesas';

    protected $fillable = [
        'numero',
        'capacidad',
        'estado',
        'mesero_id',
    ];

    // Relación con reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'mesa_id', 'id');
    }

    // Scope para mesas disponibles
    public function scopeDisponibles($query)
    {
        return $query->where('estado', 'disponible');
    }

    // Scope por capacidad
    public function scopeConCapacidad($query, $personas)
    {
        return $query->where('capacidad', '>=', $personas);
    }
}