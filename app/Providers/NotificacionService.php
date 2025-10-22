<?php
namespace App\Services;

use App\Models\Notificacion;
use App\Models\Usuario;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificacionService
{
    /**
     * Crear una nueva notificación
     */
    public function crear(array $datos)
    {
        try {
            $notificacion = Notificacion::create($datos);
            
            // Enviar email si está configurado
            if ($datos['enviar_email'] ?? false) {
                $this->enviarEmail($notificacion);
            }
            
            return $notificacion;
        } catch (\Exception $e) {
            Log::error('Error al crear notificación: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Crear notificación de pago pendiente
     */
    public function crearNotificacionPago($usuarioId, $expediente, $monto)
    {
        return $this->crear([
            'usuario_id' => $usuarioId,
            'tipo' => 'pago',
            'prioridad' => 'alta',
            'titulo' => 'Pago Pendiente - Expediente N° ' . $expediente,
            'mensaje' => "Su trámite requiere el pago de S/ {$monto}. Complete el pago para continuar con el procesamiento.",
            'referencia_tipo' => 'expediente',
            'referencia_id' => $expediente,
            'url_accion' => route('pagos.show', $expediente),
            'metadatos' => [
                'monto' => $monto,
                'moneda' => 'PEN',
                'concepto' => 'Derechos registrales'
            ]
        ]);
    }

    /**
     * Crear notificación de validación pendiente
     */
    public function crearNotificacionValidacion($usuarioId, $documento, $tipo)
    {
        return $this->crear([
            'usuario_id' => $usuarioId,
            'tipo' => 'validacion',
            'prioridad' => 'media',
            'titulo' => 'Validación Requerida - ' . $tipo,
            'mensaje' => "El documento {$documento} requiere validación del registrador. Tiempo estimado: 2-3 días hábiles.",
            'referencia_tipo' => 'documento',
            'referencia_id' => $documento,
            'url_accion' => route('validaciones.show', $documento),
            'metadatos' => [
                'tipo_documento' => $tipo,
                'estado' => 'pendiente'
            ]
        ]);
    }

    /**
     * Crear notificación de trámite completado
     */
    public function crearNotificacionTramiteCompletado($usuarioId, $expediente, $tipoDocumento)
    {
        return $this->crear([
            'usuario_id' => $usuarioId,
            'tipo' => 'tramite',
            'prioridad' => 'alta',
            'titulo' => 'Trámite Completado - ' . $tipoDocumento,
            'mensaje' => "Su {$tipoDocumento} está listo para recoger. Expediente N° {$expediente}.",
            'referencia_tipo' => 'expediente',
            'referencia_id' => $expediente,
            'url_accion' => route('tramites.show', $expediente),
            'metadatos' => [
                'tipo_documento' => $tipoDocumento,
                'estado' => 'completado'
            ]
        ]);
    }

    /**
     * Crear notificación de vencimiento próximo
     */
    public function crearNotificacionVencimiento($usuarioId, $expediente, $diasRestantes)
    {
        return $this->crear([
            'usuario_id' => $usuarioId,
            'tipo' => 'vencimiento',
            'prioridad' => 'alta',
            'titulo' => 'Vencimiento Próximo - Expediente N° ' . $expediente,
            'mensaje' => "Su trámite vence en {$diasRestantes} días. Complete la documentación requerida.",
            'referencia_tipo' => 'expediente',
            'referencia_id' => $expediente,
            'fecha_vencimiento' => now()->addDays($diasRestantes),
            'url_accion' => route('tramites.show', $expediente),
            'metadatos' => [
                'dias_restantes' => $diasRestantes,
                'tipo_vencimiento' => 'tramite'
            ]
        ]);
    }

    /**
     * Crear notificación de seguridad
     */
    public function crearNotificacionSeguridad($usuarioId, $evento, $ip = null)
    {
        return $this->crear([
            'usuario_id' => $usuarioId,
            'tipo' => 'seguridad',
            'prioridad' => 'alta',
            'titulo' => 'Alerta de Seguridad - ' . $evento,
            'mensaje' => "Se detectó actividad sospechosa en su cuenta. Evento: {$evento}",
            'referencia_tipo' => 'seguridad',
            'referencia_id' => uniqid(),
            'metadatos' => [
                'evento' => $evento,
                'ip' => $ip,
                'user_agent' => request()->userAgent()
            ]
        ]);
    }

    /**
     * Notificar a administradores
     */
    public function notificarAdministradores($titulo, $mensaje, $tipo = 'sistema')
    {
        $administradores = Usuario::where('rol', 'administrador')
                                ->where('estado', 1)
                                ->get();

        foreach ($administradores as $admin) {
            $this->crear([
                'usuario_id' => $admin->id_usuario,
                'tipo' => $tipo,
                'prioridad' => 'media',
                'titulo' => $titulo,
                'mensaje' => $mensaje,
                'referencia_tipo' => 'admin',
                'referencia_id' => 'ADMIN-' . time()
            ]);
        }
    }

    /**
     * Enviar email de notificación
     */
    private function enviarEmail($notificacion)
    {
        try {
            // Aquí implementarías el envío de email
            // Mail::to($notificacion->usuario->email_mi_acta)->send(new NotificacionMail($notificacion));
            
            $notificacion->update(['enviado_email' => true]);
        } catch (\Exception $e) {
            Log::error('Error al enviar email de notificación: ' . $e->getMessage());
        }
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function marcarTodasLeidas($usuarioId)
    {
        return Notificacion::where('usuario_id', $usuarioId)
                          ->where('leida', false)
                          ->update([
                              'leida' => true,
                              'fecha_leida' => now()
                          ]);
    }

    /**
     * Obtener estadísticas de notificaciones
     */
    public function obtenerEstadisticas($usuarioId)
    {
        $base = Notificacion::where('usuario_id', $usuarioId);
        
        return [
            'no_leidas' => $base->clone()->where('leida', false)->count(),
            'leidas_hoy' => $base->clone()->where('leida', true)
                                        ->whereDate('fecha_leida', today())
                                        ->count(),
            'total' => $base->count(),
            'por_tipo' => $base->clone()->selectRaw('tipo, COUNT(*) as cantidad')
                                      ->groupBy('tipo')
                                      ->pluck('cantidad', 'tipo')
                                      ->toArray()
        ];
    }
}
