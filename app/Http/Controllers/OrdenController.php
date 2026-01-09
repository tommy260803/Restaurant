<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\Plato; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class OrdenController extends Controller
{
    /**
     * Vista principal: Panel de mesas
     * Muestra todas las mesas con su estado REAL considerando reservas activas
     */
    public function index()
    {
        $hoy = Carbon::today(); // Fecha de hoy sin hora
        
        $mesas = Mesa::with(['reservas' => function($query) use ($hoy) {
            // ✅ CORRECCIÓN: Solo cargar reservas de HOY
            $query->whereDate('fecha_reserva', $hoy)
                  ->whereIn('estado', ['confirmada', 'pendiente'])
                  ->orderBy('hora_reserva');
        }])->orderBy('numero')->get();
        
        // Agregar estado calculado y próxima reserva a cada mesa
        $mesas->map(function ($mesa) use ($hoy) {
            // Obtener estado real considerando reservas
            $mesa->estado_calculado = $mesa->estado_real;
            
            // Verificar si tiene orden activa en sesión
            $orden = $this->obtenerOrdenMesa($mesa->id);
            $mesa->tiene_orden_activa = !empty($orden);
            
            // Obtener próxima reserva SOLO DEL DÍA DE HOY
            $mesa->proxima_reserva_hoy = $this->obtenerProximaReservaHoy($mesa, $hoy);
            
            return $mesa;
        });
        
        return view('ordenes.index', compact('mesas'));
    }

    /**
     * Abrir una mesa (cambiar a ocupada)
     */
    public function abrirMesa(Mesa $mesa)
    {
        // Verificar el estado REAL de la mesa (considerando reservas)
        $estadoReal = $mesa->estado_real;
        
        if ($estadoReal === 'reservada') {
            return redirect()->back()
                ->with('error', 'Esta mesa tiene una reserva activa en este momento. No se puede abrir para orden directa.');
        }
        
        if ($estadoReal === 'ocupada') {
            return redirect()->back()
                ->with('error', 'Esta mesa ya está ocupada.');
        }
        
        if ($estadoReal === 'mantenimiento') {
            return redirect()->back()
                ->with('error', 'Esta mesa está en mantenimiento.');
        }
        
        // Si el estado en BD es diferente al real, actualizarlo
        if ($mesa->estado !== 'ocupada') {
            $mesa->update(['estado' => 'ocupada']);
        }
        
        // Inicializar array de orden vacío en sesión
        $sessionKey = 'orden_mesa_' . $mesa->id;
        Session::put($sessionKey, [
            'mesa_id' => $mesa->id,
            'platos' => [],
            'fecha_apertura' => now(),
        ]);
        
        return redirect()->route('ordenes.ver', $mesa->id)
            ->with('success', 'Mesa #' . $mesa->numero . ' abierta correctamente');
    }

    /**
     * Ver detalle de orden de una mesa específica
     */
    public function verOrden(Mesa $mesa)
    {
        $estadoReal = $mesa->estado_real;
        
        // Permitir ver orden si está ocupada O si tiene orden activa en sesión
        $orden = $this->obtenerOrdenMesa($mesa->id);
        
        if ($estadoReal !== 'ocupada' && empty($orden)) {
            return redirect()->route('ordenes.index')
                ->with('error', 'Esta mesa no tiene una orden activa');
        }
        
        // Si no hay orden en sesión pero la mesa está marcada como ocupada, crear una vacía
        if (empty($orden)) {
            $sessionKey = 'orden_mesa_' . $mesa->id;
            Session::put($sessionKey, [
                'mesa_id' => $mesa->id,
                'platos' => [],
                'fecha_apertura' => now(),
            ]);
            $orden = Session::get($sessionKey);
        }
        
        // Calcular totales
        $total = $this->calcularTotal($orden);
        
        return view('ordenes.detalle', compact('mesa', 'orden', 'total'));
    }

    /**
     * Agregar plato a la orden (AJAX)
     */
    public function agregarPlato(Request $request, Mesa $mesa)
    {
        $request->validate([
            'plato_id' => 'required|integer',
        ]);
        
        // Buscar el plato por su primary key
        $plato = Plato::where('idPlatoProducto', $request->plato_id)->first();
        
        if (!$plato) {
            return response()->json([
                'success' => false,
                'message' => 'Plato no encontrado'
            ], 404);
        }
        
        // Verificar que el plato esté disponible
        if (!$plato->disponible) {
            return response()->json([
                'success' => false,
                'message' => 'Este plato no está disponible'
            ], 400);
        }
        
        $sessionKey = 'orden_mesa_' . $mesa->id;
        $orden = Session::get($sessionKey, []);
        
        // Inicializar orden si no existe
        if (empty($orden) || !isset($orden['platos']) || !is_array($orden['platos'])) {
            $orden = [
                'mesa_id' => $mesa->id,
                'platos' => [],
                'fecha_apertura' => now(),
            ];
        }
        
        // Verificar si el plato ya existe en la orden
        if (isset($orden['platos'][$plato->idPlatoProducto])) {
            return response()->json([
                'success' => false,
                'message' => 'Este plato ya está en la orden. Modifica la cantidad en la tabla.'
            ], 400);
        }
        
        // Agregar plato con cantidad inicial de 1
        $orden['platos'][$plato->idPlatoProducto] = [
            'id' => $plato->idPlatoProducto,
            'nombre' => $plato->nombre,
            'precio' => $plato->precio,
            'cantidad' => 1,
            'nota' => '',
        ];
        
        Session::put($sessionKey, $orden);
        
        $total = $this->calcularTotal($orden);
        
        return response()->json([
            'success' => true,
            'message' => 'Plato agregado correctamente',
            'plato' => $orden['platos'][$plato->idPlatoProducto],
            'total' => $total
        ]);
    }

    /**
     * Actualizar cantidad de un plato (AJAX)
     */
    public function actualizarCantidad(Request $request, Mesa $mesa)
    {
        $request->validate([
            'plato_id' => 'required|integer',
            'cantidad' => 'required|integer|min:1|max:50',
        ]);
        
        $sessionKey = 'orden_mesa_' . $mesa->id;
        $orden = Session::get($sessionKey, []);
        
        if (!isset($orden['platos'][$request->plato_id])) {
            return response()->json([
                'success' => false,
                'message' => 'Plato no encontrado en la orden'
            ], 404);
        }
        
        // Actualizar cantidad
        $orden['platos'][$request->plato_id]['cantidad'] = $request->cantidad;
        Session::put($sessionKey, $orden);
        
        $subtotal = $orden['platos'][$request->plato_id]['precio'] * $request->cantidad;
        $total = $this->calcularTotal($orden);
        
        return response()->json([
            'success' => true,
            'subtotal' => $subtotal,
            'total' => $total
        ]);
    }

    /**
     * Eliminar plato de la orden (AJAX)
     */
    public function eliminarPlato(Mesa $mesa, $plato)
    {
        $sessionKey = 'orden_mesa_' . $mesa->id;
        $orden = Session::get($sessionKey, []);
        
        if (!isset($orden['platos'][$plato])) {
            return response()->json([
                'success' => false,
                'message' => 'Plato no encontrado'
            ], 404);
        }
        
        unset($orden['platos'][$plato]);
        Session::put($sessionKey, $orden);
        
        $total = $this->calcularTotal($orden);
        
        return response()->json([
            'success' => true,
            'message' => 'Plato eliminado',
            'total' => $total
        ]);
    }

    /**
     * Actualizar nota de un plato (AJAX)
     */
    public function actualizarNota(Request $request, Mesa $mesa)
    {
        $request->validate([
            'plato_id' => 'required|integer',
            'nota' => 'nullable|string|max:500',
        ]);
        
        $sessionKey = 'orden_mesa_' . $mesa->id;
        $orden = Session::get($sessionKey, []);
        
        if (!isset($orden['platos'][$request->plato_id])) {
            return response()->json([
                'success' => false,
                'message' => 'Plato no encontrado'
            ], 404);
        }
        
        $orden['platos'][$request->plato_id]['nota'] = $request->nota ?? '';
        Session::put($sessionKey, $orden);
        
        return response()->json([
            'success' => true,
            'message' => 'Nota actualizada'
        ]);
    }

    /**
     * Procesar cobro y cerrar mesa
     */
    public function cobrar(Mesa $mesa)
    {
        $sessionKey = 'orden_mesa_' . $mesa->id;
        $orden = Session::get($sessionKey, []);
        
        if (empty($orden['platos'])) {
            return redirect()->back()
                ->with('error', 'No hay platos en la orden para cobrar');
        }
        
        $total = $this->calcularTotal($orden);
        
        // TODO: Crear registro en tabla de ventas si existe
        // Venta::create([...]);
        
        // Limpiar orden de sesión
        Session::forget($sessionKey);
        
        // Cambiar estado de mesa a disponible
        $mesa->update(['estado' => 'disponible']);
        
        return redirect()->route('ordenes.index')
            ->with('success', 'Orden cobrada exitosamente. Total: $' . number_format($total, 2));
    }

    /**
     * Volver/Cancelar orden (liberar mesa sin cobrar)
     */
    /**
     * Volver a la vista anterior SIN cancelar la orden
     * La mesa y la orden se mantienen intactas
     */
    public function volver(Mesa $mesa)
    {
        // Solo redirigir sin hacer cambios
        return redirect()->route('ordenes.index');
    }

    /**
     * Cancelar orden y liberar mesa (nuevo método)
     */
    public function cancelar(Mesa $mesa)
    {
        $sessionKey = 'orden_mesa_' . $mesa->id;
        
        // Limpiar orden
        Session::forget($sessionKey);
        
        // Liberar mesa
        $mesa->update(['estado' => 'disponible']);
        
        return redirect()->route('ordenes.index')
            ->with('success', 'Orden cancelada y mesa liberada correctamente');
    }

    /**
     * Obtener platos disponibles para el modal (AJAX)
     */
    public function getPlatosDisponibles(Request $request)
    {
        $query = Plato::with('categoria')
            ->where('disponible', 1);
        
        // Búsqueda por nombre
        if ($request->filled('buscar')) {
            $query->where('nombre', 'LIKE', '%' . $request->buscar . '%');
        }
        
        $platos = $query->orderBy('nombre', 'asc')->get();
        
        // Obtener IDs de platos ya agregados a la mesa actual
        $mesaId = $request->input('mesa_id');
        $platosEnOrden = [];
        
        if ($mesaId) {
            $orden = $this->obtenerOrdenMesa($mesaId);
            $platosEnOrden = array_keys($orden['platos'] ?? []);
        }
        
        return response()->json([
            'success' => true,
            'platos' => $platos,
            'platos_en_orden' => $platosEnOrden
        ]);
    }

    // ============================================
    // MÉTODOS PRIVADOS AUXILIARES
    // ============================================

    /**
     * Obtener orden de una mesa desde sesión
     */
    private function obtenerOrdenMesa($mesaId)
    {
        $sessionKey = 'orden_mesa_' . $mesaId;
        return Session::get($sessionKey, []);
    }

    /**
     * Calcular total de la orden
     */
    private function calcularTotal($orden)
    {
        $total = 0;
        
        if (isset($orden['platos']) && is_array($orden['platos'])) {
            foreach ($orden['platos'] as $plato) {
                $total += $plato['precio'] * $plato['cantidad'];
            }
        }
        
        return $total;
    }

    private function obtenerProximaReservaHoy($mesa, $fechaHoy)
    {
        $ahora = Carbon::now();
        
        return $mesa->reservas()
            ->whereDate('fecha_reserva', $fechaHoy)
            ->whereIn('estado', ['confirmada', 'pendiente'])
            ->where(function($query) use ($ahora) {
                // Solo mostrar si la hora aún no ha pasado
                $query->whereRaw("TIME(hora_reserva) >= ?", [$ahora->format('H:i:s')]);
            })
            ->orderBy('hora_reserva')
            ->first();
    }
}
