<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $fillable = [
        'usuario_id',
        'tipo',
        'prioridad',
        'titulo',
        'mensaje',
        'referencia_tipo',
        'referencia_id',
        'url_accion',
        'metadatos',
        'leida',
        'fecha_leida',
        'fecha_vencimiento',
        'enviado_email'
    ];

    protected $casts = [
        'metadatos' => 'array',
        'leida' => 'boolean',
        'enviado_email' => 'boolean',
        'fecha_leida' => 'datetime',
        'fecha_vencimiento' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relaciones
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id_usuario');
    }

    // Scopes
    public function scopeNoLeidas($query)
    {
        return $query->where('leida', false);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopePorPrioridad($query, $prioridad)
    {
        return $query->where('prioridad', $prioridad);
    }

    public function scopeVencidas($query)
    {
        return $query->where('fecha_vencimiento', '<', now())
                    ->where('leida', false);
    }

    public function scopeRecientes($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Métodos
    public function marcarComoLeida()
    {
        $this->update([
            'leida' => true,
            'fecha_leida' => now()
        ]);
    }

    public function estaVencida()
    {
        return $this->fecha_vencimiento && $this->fecha_vencimiento->isPast();
    }

    public function getIconoAttribute()
    {
        $iconos = [
            'pago' => 'ri-money-dollar-circle-line',
            'validacion' => 'ri-shield-check-line',
            'tramite' => 'ri-file-text-line',
            'vencimiento' => 'ri-calendar-check-line',
            'seguridad' => 'ri-shield-keyhole-line',
            'sistema' => 'ri-settings-3-line'
        ];

        return $iconos[$this->tipo] ?? 'ri-notification-3-line';
    }

    public function getColorAttribute()
    {
        $colores = [
            'pago' => 'warning',
            'validacion' => 'info',
            'tramite' => 'success',
            'vencimiento' => 'danger',
            'seguridad' => 'danger',
            'sistema' => 'primary'
        ];

        return $colores[$this->tipo] ?? 'secondary';
    }
}