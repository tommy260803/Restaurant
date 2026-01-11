<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenPlato extends Model
{
    use HasFactory;

    protected $table = 'orden_platos';

    protected $fillable = [
        'orden_id',
        'plato_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'estado_cocina',
        'notas',
        'es_preorden', // ✅ NUEVO CAMPO
        'enviado_cocina_at',
        'en_preparacion_at',
        'preparado_at',
        'entregado_at',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'es_preorden' => 'boolean', // ✅ CAST A BOOLEAN
        'enviado_cocina_at' => 'datetime',
        'en_preparacion_at' => 'datetime',
        'preparado_at' => 'datetime',
        'entregado_at' => 'datetime',
    ];

    // Relaciones
    public function orden()
    {
        return $this->belongsTo(Orden::class, 'orden_id', 'id');
    }

    public function plato()
    {
        return $this->belongsTo(Plato::class, 'plato_id', 'idPlatoProducto');
    }

    // Scopes
    public function scopeEnviadosACocina($query)
    {
        return $query->where('estado_cocina', 'Enviado a cocina');
    }

    public function scopeEnPreparacion($query)
    {
        return $query->where('estado_cocina', 'En preparación');
    }

    public function scopePreparados($query)
    {
        return $query->where('estado_cocina', 'Preparado');
    }

    public function scopeEntregados($query)
    {
        return $query->where('estado_cocina', 'Entregado');
    }

    // ✅ NUEVO: Scope para platos de pre-orden
    public function scopePreOrden($query)
    {
        return $query->where('es_preorden', true);
    }

    // ✅ NUEVO: Scope para platos agregados después
    public function scopeAgregadosDespues($query)
    {
        return $query->where('es_preorden', false);
    }

    // Métodos auxiliares
    public function calcularSubtotal()
    {
        return $this->cantidad * $this->precio_unitario;
    }

    protected static function boot()
    {
        parent::boot();

        // Al crear, calcular subtotal automáticamente
        static::creating(function ($ordenPlato) {
            $ordenPlato->subtotal = $ordenPlato->calcularSubtotal();
            $ordenPlato->enviado_cocina_at = now();
        });

        // Al actualizar cantidad, recalcular subtotal
        static::updating(function ($ordenPlato) {
            if ($ordenPlato->isDirty('cantidad') || $ordenPlato->isDirty('precio_unitario')) {
                $ordenPlato->subtotal = $ordenPlato->calcularSubtotal();
            }
        });

        // ✅ SOLO cuando se crea un plato
        static::created(function ($ordenPlato) {
            $ordenPlato->orden->actualizarTotal();
        });

        // ✅ SOLO cuando cambian valores que afectan el total
        static::updated(function ($ordenPlato) {
            if ($ordenPlato->wasChanged(['cantidad', 'precio_unitario', 'subtotal'])) {
                $ordenPlato->orden->actualizarTotal();
            }
        });

        static::deleted(function ($ordenPlato) {
            $ordenPlato->orden->actualizarTotal();
        });
    }
}