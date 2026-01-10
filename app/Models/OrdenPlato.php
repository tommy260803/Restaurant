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
        'enviado_cocina_at',
        'en_preparacion_at',
        'preparado_at',
        'entregado_at',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
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

        // Después de crear/actualizar/eliminar, actualizar total de la orden
        static::saved(function ($ordenPlato) {
            $ordenPlato->orden->actualizarTotal();
        });

        static::deleted(function ($ordenPlato) {
            $ordenPlato->orden->actualizarTotal();
        });
    }
}