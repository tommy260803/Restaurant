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
     * MÉTODO CORREGIDO: Estado real de la mesa
     * ============================================
     */
    public function getEstadoRealAttribute()
    {
        // Prioridad 1: Mantenimiento siempre es mantenimiento
        if ($this->estado === 'mantenimiento') {
            return 'mantenimiento';
        }

        $ahora = Carbon::now();
        
        // ✅ PRIMERO: Verificar si hay reserva activa ANTES de revisar el estado en BD
        $tipoReserva = $this->reservas()
            ->whereIn('estado', ['confirmada', 'pendiente'])
            ->where('fecha_reserva', $ahora->toDateString())
            ->get()
            ->first(function($reserva) use ($ahora) {
                $horaReserva = Carbon::parse($reserva->fecha_reserva->toDateString() . ' ' . $reserva->hora_reserva);
                
                $inicioBloqueo = $horaReserva->copy()->subMinutes(30);
                $inicioReserva = $horaReserva->copy();
                $finVentana = $horaReserva->copy()->addHours(3);
                
                if ($ahora->between($inicioBloqueo, $inicioReserva)) {
                    return 'proxima';
                }
                
                if ($ahora->between($inicioReserva, $finVentana)) {
                    return 'activa';
                }
                
                return false;
            });

        // Si hay reserva próxima (30 min antes)
        if ($tipoReserva === 'proxima') {
            return 'reservada';
        }
        
        // ✅ CRÍTICO: Si hay reserva activa (hora llegó)
        if ($tipoReserva === 'activa') {
            // Actualizar estado en BD si no está ocupada
            try {
                if ($this->estado !== 'ocupada') {
                    \DB::table('mesas')->where('id', $this->id)->update(['estado' => 'ocupada']);
                    $this->estado = 'ocupada'; // Actualizar atributo en memoria
                }
            } catch (\Exception $e) {
                \Log::error('Error actualizando estado de mesa: ' . $e->getMessage());
            }
            return 'ocupada';
        }

        // Si está ocupada en BD (con orden activa)
        if ($this->estado === 'ocupada') {
            return 'ocupada';
        }

        // Por defecto: disponible
        return 'disponible';
    }

    
    public function actualizarEstadoSiTieneReservaActiva()
{
    $ahora = Carbon::now();
    
    $tieneReservaActiva = $this->reservas()
        ->whereIn('estado', ['confirmada', 'pendiente'])
        ->where('fecha_reserva', $ahora->toDateString())
        ->get()
        ->contains(function($reserva) use ($ahora) {
            $horaReserva = Carbon::parse($reserva->fecha_reserva->toDateString() . ' ' . $reserva->hora_reserva);
            $inicioReserva = $horaReserva->copy();
            $finVentana = $horaReserva->copy()->addHours(3);
            
            return $ahora->between($inicioReserva, $finVentana);
        });
    
    if ($tieneReservaActiva && $this->estado !== 'ocupada') {
        $this->update(['estado' => 'ocupada']);
    }
    
    return $this;
}
    /**
     * Verificar si tiene reserva activa (hora ya llegó)
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
                $inicioReserva = $horaReserva->copy();
                $finVentana = $horaReserva->copy()->addHours(3);
                
                // Solo si YA llegó la hora
                return $ahora->between($inicioReserva, $finVentana);
            });
    }

    /**
     * Verificar si tiene reserva próxima (30 min antes)
     */
    public function tieneReservaProxima()
    {
        $ahora = Carbon::now();
        
        return $this->reservas()
            ->whereIn('estado', ['confirmada', 'pendiente'])
            ->where('fecha_reserva', $ahora->toDateString())
            ->get()
            ->contains(function($reserva) use ($ahora) {
                $horaReserva = Carbon::parse($reserva->fecha_reserva->toDateString() . ' ' . $reserva->hora_reserva);
                $inicioBloqueo = $horaReserva->copy()->subMinutes(30);
                $inicioReserva = $horaReserva->copy();
                
                // Solo si estamos en la ventana ANTES de la hora
                return $ahora->between($inicioBloqueo, $inicioReserva);
            });
    }

    /**
     * Obtener la reserva activa actual (si existe)
     */
    public function getReservaActivaAttribute()
    {
        $ahora = Carbon::now();
        
        return $this->reservas()
            ->whereIn('estado', ['confirmada', 'pendiente'])
            ->where('fecha_reserva', $ahora->toDateString())
            ->get()
            ->first(function($reserva) use ($ahora) {
                $horaReserva = Carbon::parse($reserva->fecha_reserva->toDateString() . ' ' . $reserva->hora_reserva);
                $inicioReserva = $horaReserva->copy();
                $finVentana = $horaReserva->copy()->addHours(3);
                
                return $ahora->between($inicioReserva, $finVentana);
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