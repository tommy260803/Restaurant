<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    /**
     * ============================================
     * NUEVO: Método para obtener el estado REAL de la mesa
     * considerando reservas activas según fecha/hora
     * ============================================
     */
    public function getEstadoRealAttribute()
    {
        // Si la mesa está en mantenimiento, siempre retornar mantenimiento
        if ($this->estado === 'mantenimiento') {
            return 'mantenimiento';
        }

        // Si la mesa está ocupada por una orden activa (no por reserva), retornar ocupada
        if ($this->estado === 'ocupada') {
            return 'ocupada';
        }

        // Verificar si hay una reserva ACTIVA en este momento
        $ahora = Carbon::now();
        
        $reservaActiva = $this->reservas()
            ->whereIn('estado', ['confirmada', 'pendiente'])
            ->where('fecha_reserva', $ahora->toDateString())
            ->get()
            ->first(function($reserva) use ($ahora) {
                // Parsear hora de reserva
                $horaReserva = Carbon::parse($reserva->fecha_reserva->toDateString() . ' ' . $reserva->hora_reserva);
                
                // Considerar que la reserva está "activa" si estamos dentro de una ventana de tiempo:
                // - 30 minutos antes de la hora (margen de llegada temprana)
                // - 2 horas después (duración estimada de la reserva)
                $inicioVentana = $horaReserva->copy()->subMinutes(30);
                $finVentana = $horaReserva->copy()->addHours(2);
                
                return $ahora->between($inicioVentana, $finVentana);
            });

        if ($reservaActiva) {
            return 'reservada'; // Tiene reserva activa AHORA
        }

        // Si no hay reserva activa, la mesa está disponible
        return 'disponible';
    }

    /**
     * Método alternativo: verificar si tiene reserva activa
     */
    public function tieneReservaActiva()
    {
        $ahora = Carbon::now();
        
        return $this->reservas()
            ->whereIn('estado', ['confirmada', 'pendiente'])
            ->where('fecha_reserva', $ahora->toDateString())
            ->get()
            ->contains(function($reserva) use ($ahora) {
                $horaReserva = Carbon::parse($reserva->fecha_reserva->toDateString() . ' ' . $reserva->hora_reserva);
                $inicioVentana = $horaReserva->copy()->subMinutes(30);
                $finVentana = $horaReserva->copy()->addHours(2);
                
                return $ahora->between($inicioVentana, $finVentana);
            });
    }

    /**
     * Obtener la próxima reserva de esta mesa
     */
    public function getProximaReservaAttribute()
    {
        $ahora = Carbon::now();
        
        return $this->reservas()
            ->whereIn('estado', ['confirmada', 'pendiente'])
            ->where(function($query) use ($ahora) {
                $query->where('fecha_reserva', '>', $ahora->toDateString())
                      ->orWhere(function($q) use ($ahora) {
                          $q->where('fecha_reserva', $ahora->toDateString())
                            ->whereRaw("TIME(hora_reserva) > ?", [$ahora->format('H:i:s')]);
                      });
            })
            ->orderBy('fecha_reserva')
            ->orderBy('hora_reserva')
            ->first();
    }
}