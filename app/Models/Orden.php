<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;

    protected $table = 'ordenes';

    protected $fillable = [
        'mesa_id',
        'estado',
        'total',
        'abierta_por',
        'fecha_apertura',
        'fecha_cierre',
    ];

    protected $casts = [
        'fecha_apertura' => 'datetime',
        'fecha_cierre' => 'datetime',
        'total' => 'decimal:2',
    ];

    // Relaciones
    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'mesa_id', 'id');
    }

    public function platos()
    {
        return $this->hasMany(OrdenPlato::class, 'orden_id', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'abierta_por', 'idUsuario');
    }

    // Scopes
    public function scopeAbiertas($query)
    {
        return $query->where('estado', 'abierta');
    }

    public function scopePagadas($query)
    {
        return $query->where('estado', 'pagada');
    }

    public function scopeCanceladas($query)
    {
        return $query->where('estado', 'cancelada');
    }

    // Métodos auxiliares
    public function calcularTotal()
    {
        return $this->platos()->sum('subtotal');
    }

    public function actualizarTotal()
    {
        $this->total = $this->calcularTotal();
        $this->save();
    }
}