<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reserva extends Model
{
    use HasFactory;

    // Nombre de la tabla (Laravel por defecto busca 'reservas')
    protected $table = 'reservas';

    // Campos permitidos para asignación masiva
    protected $fillable = [
        'idCliente',
        'mesa_id',
        'nombre_cliente',
        'email',
        'telefono',
        'fecha_reserva',
        'hora_reserva',
        'numero_personas',
        'comentarios',
        'estado',
    ];

    // Casting de campos
    protected $casts = [
        'fecha_reserva' => 'date',
        // Removido casting de hora_reserva - se guarda como string TIME
    ];

    // Relación con Cliente (puede ser NULL)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente', 'idCliente');
    }

    // Relación con Mesa (puede ser NULL)
    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'mesa_id', 'id');
    }

    // Relación con Platos (pre-orden opcional)
    public function platos()
    {
        return $this->belongsToMany(Plato::class, 'reserva_platos', 'reserva_id', 'plato_id')
                    ->withPivot('cantidad', 'precio')
                    ->withTimestamps();
    }

    // Scopes para filtros comunes
    public function scopeHoy($query)
    {
        return $query->whereDate('fecha_reserva', today());
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeConfirmadas($query)
    {
        return $query->where('estado', 'confirmada');
    }

    public function scopeProximas($query)
    {
        return $query->where('fecha_reserva', '>=', today())
                     ->orderBy('fecha_reserva')
                     ->orderBy('hora_reserva');
    }

    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    public function scopeCanceladas($query)
    {
        return $query->where('estado', 'cancelada');
    }

    // Accessor para formato de hora legible
    public function getHoraFormateadaAttribute()
    {
        // Convertir string de hora (HH:MM:SS) a formato 12h
        return Carbon::parse($this->hora_reserva)->format('g:i A');
    }

    // Accessor para fecha legible
    public function getFechaFormateadaAttribute()
    {
        return $this->fecha_reserva->locale('es')->translatedFormat('l, d \d\e F \d\e Y');
    }

    // Método para obtener el subtotal de platos pre-ordenados
    public function getSubtotalPlatosAttribute()
    {
        return $this->platos->sum(function($plato) {
            return $plato->pivot->cantidad * $plato->pivot->precio;
        });
    }

    // Método para verificar si tiene platos pre-ordenados
    public function tienePlatosPedidos()
    {
        return $this->platos()->count() > 0;
    }
}