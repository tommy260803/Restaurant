<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notificacion;
use App\Services\NotificacionService;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    protected $notificacionService;

    public function __construct(NotificacionService $notificacionService)
    {
        $this->notificacionService = $notificacionService;
    }

    /**
     * Mostrar lista de notificaciones
     */
    public function index(Request $request)
    {
        $usuarioId = Auth::id();
        
        $query = Notificacion::where('usuario_id', $usuarioId);

        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('estado')) {
            if ($request->estado === 'no_leida') {
                $query->where('leida', false);
            } elseif ($request->estado === 'leida') {
                $query->where('leida', true);
            }
        }

        if ($request->filled('prioridad')) {
            $query->where('prioridad', $request->prioridad);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('titulo', 'like', "%{$buscar}%")
                  ->orWhere('mensaje', 'like', "%{$buscar}%");
            });
        }

        $notificaciones = $query->orderBy('created_at', 'desc')->paginate(20);

        // Estadísticas
       $estadisticas = $this->notificacionService->obtenerEstadisticas($usuarioId);


        return view('admin.usuario.form-perfil.datosNotificacion', [
            'notificaciones' => $notificaciones,
            'notificaciones_no_leidas' => $estadisticas['no_leidas'],
            'validaciones_pendientes' => $estadisticas['por_tipo']['validacion'] ?? 0,
            'pagos_pendientes' => $estadisticas['por_tipo']['pago'] ?? 0,
            'tramites_hoy' => $estadisticas['leidas_hoy']
        ]);
    }

    /**
     * Marcar notificación como leída
     */
    public function marcarLeida(Request $request, $id)
    {
        $notificacion = Notificacion::where('id', $id)
                                  ->where('usuario_id', Auth::id())
                                  ->first();

        if ($notificacion) {
            $notificacion->marcarComoLeida();
            
            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }
            
            return redirect()->back()->with('success', 'Notificación marcada como leída');
        }

        return response()->json(['success' => false], 404);
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function marcarTodasLeidas(Request $request)
    {
        $cantidad = $this->notificacionService->marcarTodasLeidas(Auth::id());
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'cantidad' => $cantidad
            ]);
        }
        
        return redirect()->back()->with('success', "Se marcaron {$cantidad} notificaciones como leídas");
    }

    /**
     * Eliminar notificación
     */
    public function destroy(Request $request, $id)
    {
        $notificacion = Notificacion::where('id', $id)
                                  ->where('usuario_id', Auth::id())
                                  ->first();

        if ($notificacion) {
            $notificacion->delete();
            
            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }
            
            return redirect()->back()->with('success', 'Notificación eliminada');
        }

        return response()->json(['success' => false], 404);
    }

    /**
     * Verificar nuevas notificaciones (AJAX)
     */
    public function verificarNuevas(Request $request)
    {
        $ultimaVerificacion = $request->input('ultima_verificacion', now()->subMinutes(1));
        
        $nuevas = Notificacion::where('usuario_id', Auth::id())
                            ->where('leida', false)
                            ->where('created_at', '>', $ultimaVerificacion)
                            ->count();

        return response()->json(['nuevas' => $nuevas]);
    }

    /**
     * Obtener notificaciones para el menú (AJAX)
     */
    public function obtenerParaMenu()
    {
        $notificaciones = Notificacion::where('usuario_id', Auth::id())
                                    ->where('leida', false)
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();

        return response()->json([
            'notificaciones' => $notificaciones,
            'total_no_leidas' => Notificacion::where('usuario_id', Auth::id())
                                           ->where('leida', false)
                                           ->count()
        ]);
    }

    /**
     * Guardar configuración de notificaciones
     */
    public function guardarConfiguracion(Request $request)
    {
        // Aquí guardarías las preferencias del usuario
        // Por ejemplo, en una tabla user_preferences o en el modelo Usuario
        
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->back()->with('success', 'Configuración guardada correctamente');
    }
}