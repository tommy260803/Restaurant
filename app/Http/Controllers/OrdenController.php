<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\Plato;
use App\Models\Reserva;
use App\Models\Orden;
use App\Models\OrdenPlato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrdenController extends Controller
{
    /**
     * Vista principal: Panel de mesas
     * Muestra todas las mesas con su estado REAL considerando reservas activas
     */
public function index()
    {
        $hoy = Carbon::today();
        
        $mesas = Mesa::with(['reservas' => function($query) use ($hoy) {
            $query->whereDate('fecha_reserva', $hoy)
                  ->whereIn('estado', ['confirmada', 'pendiente', 'completada'])
                  ->with('platos')
                  ->orderBy('hora_reserva');
        }])->orderBy('numero')->get();
        
        $mesas->map(function ($mesa) use ($hoy) {
            $mesa->estado_calculado = $mesa->estado_real;
            
            // Verificar si tiene orden activa en BD
            $mesa->tiene_orden_activa = Orden::where('mesa_id', $mesa->id)
                ->where('estado', 'abierta')
                ->exists();
            
            $mesa->proxima_reserva_hoy = $this->obtenerProximaReservaHoy($mesa, $hoy);
            $mesa->es_reserva = $this->mesaOcupadaPorReserva($mesa);
            
            return $mesa;
        });
        
        return view('ordenes.index', compact('mesas'));
    }

    /**
     * Abrir una mesa (cambiar a ocupada)
     */
    public function abrirMesa(Mesa $mesa)
    {
        $estadoReal = $mesa->estado_real;
        
        if ($estadoReal === 'reservada') {
            return redirect()->back()
                ->with('error', 'Esta mesa tiene una reserva activa en este momento.');
        }
        
        if ($estadoReal === 'ocupada') {
            return redirect()->back()
                ->with('error', 'Esta mesa ya está ocupada.');
        }
        
        if ($estadoReal === 'mantenimiento') {
            return redirect()->back()
                ->with('error', 'Esta mesa está en mantenimiento.');
        }

        DB::beginTransaction();
        try {
            // Cambiar estado de mesa
            $mesa->update(['estado' => 'ocupada']);
            
            // Crear orden en BD
            $orden = Orden::create([
                'mesa_id' => $mesa->id,
                'estado' => 'abierta',
                'total' => 0,
                'abierta_por' => Auth::id(),
                'fecha_apertura' => now(),
            ]);
            
            DB::commit();
            
            return redirect()->route('ordenes.ver', $mesa->id)
                ->with('success', 'Mesa #' . $mesa->numero . ' abierta correctamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al abrir la mesa: ' . $e->getMessage());
        }
    }

    /**
     * Ver detalle de orden de una mesa específica
     */
    public function verOrden(Mesa $mesa)
    {
        $estadoReal = $mesa->estado_real;
        
        // Buscar orden activa en BD
        $orden = Orden::with('platos.plato')
            ->where('mesa_id', $mesa->id)
            ->where('estado', 'abierta')
            ->first();
        
        if ($estadoReal !== 'ocupada' && !$orden) {
            return redirect()->route('ordenes.index')
                ->with('error', 'Esta mesa no tiene una orden activa');
        }
        
        // Verificar si hay una reserva activa con platos pre-ordenados
        $reservaActiva = $this->obtenerReservaActivaConPlatos($mesa);
        $esReserva = false;
        $reserva = null;
        
        if ($reservaActiva && !$orden) {
            // Crear orden y cargar platos de la reserva
            DB::beginTransaction();
            try {
                $orden = Orden::create([
                    'mesa_id' => $mesa->id,
                    'estado' => 'abierta',
                    'total' => 0,
                    'abierta_por' => Auth::id(),
                    'fecha_apertura' => now(),
                ]);
                
                foreach ($reservaActiva->platos as $plato) {
                    OrdenPlato::create([
                        'orden_id' => $orden->id,
                        'plato_id' => $plato->idPlatoProducto,
                        'cantidad' => $plato->pivot->cantidad,
                        'precio_unitario' => $plato->pivot->precio,
                        'notas' => $plato->pivot->notas ?? '',
                        'estado_cocina' => 'Enviado a cocina',
                    ]);
                }
                
                DB::commit();
                $esReserva = true;
                $reserva = $reservaActiva;
                
                // Recargar orden con platos
                $orden = $orden->fresh(['platos.plato']);
                
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Error al cargar platos de reserva: ' . $e->getMessage());
            }
        } elseif (!$orden) {
            // Crear orden vacía si no existe
            $orden = Orden::create([
                'mesa_id' => $mesa->id,
                'estado' => 'abierta',
                'total' => 0,
                'abierta_por' => Auth::id(),
                'fecha_apertura' => now(),
            ]);
        } else {
            // Verificar si la orden es de una reserva
            if ($reservaActiva) {
                $esReserva = true;
                $reserva = $reservaActiva;
            }
        }
        
        $total = $orden->total ?? 0;
        
        return view('ordenes.detalle', compact('mesa', 'orden', 'total', 'esReserva', 'reserva'));
    }

    /**
     * Agregar plato a la orden (AJAX)
     */
    public function agregarPlato(Request $request, Mesa $mesa)
    {
        $request->validate([
            'plato_id' => 'required|integer',
        ]);
        
        $plato = Plato::where('idPlatoProducto', $request->plato_id)->first();
        
        if (!$plato) {
            return response()->json([
                'success' => false,
                'message' => 'Plato no encontrado'
            ], 404);
        }
        
        if (!$plato->disponible) {
            return response()->json([
                'success' => false,
                'message' => 'Este plato no está disponible'
            ], 400);
        }
        
        // Buscar orden activa
        $orden = Orden::where('mesa_id', $mesa->id)
            ->where('estado', 'abierta')
            ->first();
        
        if (!$orden) {
            return response()->json([
                'success' => false,
                'message' => 'No hay orden activa para esta mesa'
            ], 400);
        }
        
        // Verificar si el plato ya existe en la orden
        $platoExistente = OrdenPlato::where('orden_id', $orden->id)
            ->where('plato_id', $plato->idPlatoProducto)
            ->first();
        
        if ($platoExistente) {
            return response()->json([
                'success' => false,
                'message' => 'Este plato ya está en la orden. Modifica la cantidad en la tabla.'
            ], 400);
        }
        
        DB::beginTransaction();
        try {
            // ✅ Crear plato en BD - SE ENVÍA A COCINA AUTOMÁTICAMENTE
            $ordenPlato = OrdenPlato::create([
                'orden_id' => $orden->id,
                'plato_id' => $plato->idPlatoProducto,
                'cantidad' => 1,
                'precio_unitario' => $plato->precio,
                'notas' => '',
                'estado_cocina' => 'Enviado a cocina',
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Plato agregado y enviado a cocina',
                'plato' => [
                    'id' => $ordenPlato->id,
                    'plato_id' => $plato->idPlatoProducto,
                    'nombre' => $plato->nombre,
                    'precio' => $plato->precio,
                    'cantidad' => 1,
                    'subtotal' => $ordenPlato->subtotal,
                ],
                'total' => $orden->fresh()->total
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar plato: ' . $e->getMessage()
            ], 500);
        }
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
        
        $orden = Orden::where('mesa_id', $mesa->id)
            ->where('estado', 'abierta')
            ->first();
        
        if (!$orden) {
            return response()->json([
                'success' => false,
                'message' => 'Orden no encontrada'
            ], 404);
        }
        
        $ordenPlato = OrdenPlato::where('id', $request->plato_id)
            ->where('orden_id', $orden->id)
            ->first();
        
        if (!$ordenPlato) {
            return response()->json([
                'success' => false,
                'message' => 'Plato no encontrado en la orden'
            ], 404);
        }
        
        DB::beginTransaction();
        try {
            $ordenPlato->update(['cantidad' => $request->cantidad]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'subtotal' => $ordenPlato->fresh()->subtotal,
                'total' => $orden->fresh()->total
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar cantidad'
            ], 500);
        }
    }

    /**
     * Eliminar plato de la orden (AJAX)
     */
    public function eliminarPlato(Mesa $mesa, $platoId)
    {
        $orden = Orden::where('mesa_id', $mesa->id)
            ->where('estado', 'abierta')
            ->first();
        
        if (!$orden) {
            return response()->json([
                'success' => false,
                'message' => 'Orden no encontrada'
            ], 404);
        }
        
        $ordenPlato = OrdenPlato::where('id', $platoId)
            ->where('orden_id', $orden->id)
            ->first();
        
        if (!$ordenPlato) {
            return response()->json([
                'success' => false,
                'message' => 'Plato no encontrado'
            ], 404);
        }
        
        DB::beginTransaction();
        try {
            $ordenPlato->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Plato eliminado',
                'total' => $orden->fresh()->total
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar plato'
            ], 500);
        }
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
        
        $orden = Orden::where('mesa_id', $mesa->id)
            ->where('estado', 'abierta')
            ->first();
        
        if (!$orden) {
            return response()->json([
                'success' => false,
                'message' => 'Orden no encontrada'
            ], 404);
        }
        
        $ordenPlato = OrdenPlato::where('id', $request->plato_id)
            ->where('orden_id', $orden->id)
            ->first();
        
        if (!$ordenPlato) {
            return response()->json([
                'success' => false,
                'message' => 'Plato no encontrado'
            ], 404);
        }
        
        try {
            $ordenPlato->update(['notas' => $request->nota ?? '']);
            
            return response()->json([
                'success' => true,
                'message' => 'Nota actualizada'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar nota'
            ], 500);
        }
    }

    /**
     * Procesar cobro y cerrar mesa
     */
    public function cobrar(Mesa $mesa)
    {
        $orden = Orden::with('platos')
            ->where('mesa_id', $mesa->id)
            ->where('estado', 'abierta')
            ->first();
        
        if (!$orden || $orden->platos->isEmpty()) {
            return redirect()->back()
                ->with('error', 'No hay platos en la orden para cobrar');
        }
        
        DB::beginTransaction();
        try {
            // Marcar orden como pagada
            $orden->update([
                'estado' => 'pagada',
                'fecha_cierre' => now(),
            ]);
            
            // Marcar todos los platos como entregados
            $orden->platos()->update([
                'estado_cocina' => 'Entregado',
                'entregado_at' => now(),
            ]);
            
            // Si hay reserva asociada, completarla
            $reservaActiva = $this->obtenerReservaActivaConPlatos($mesa);
            if ($reservaActiva) {
                $reservaActiva->update(['estado' => 'completada']);
            }
            
            // Liberar mesa
            $mesa->update(['estado' => 'disponible']);
            
            DB::commit();
            
            return redirect()->route('ordenes.index')
                ->with('success', 'Orden cobrada exitosamente. Total: $' . number_format($orden->total, 2));
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al cobrar: ' . $e->getMessage());
        }
    }

    /**
     * Volver a la vista anterior SIN cancelar la orden
     */
    public function volver(Mesa $mesa)
    {
        return redirect()->route('ordenes.index');
    }

    /**
     * Cancelar orden y liberar mesa
     */
    public function cancelar(Mesa $mesa)
    {
        $orden = Orden::where('mesa_id', $mesa->id)
            ->where('estado', 'abierta')
            ->first();
        
        if (!$orden) {
            return redirect()->route('ordenes.index')
                ->with('error', 'No hay orden activa para cancelar');
        }
        
        DB::beginTransaction();
        try {
            $orden->update(['estado' => 'cancelada']);
            $mesa->update(['estado' => 'disponible']);
            
            DB::commit();
            
            return redirect()->route('ordenes.index')
                ->with('success', 'Orden cancelada y mesa liberada correctamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al cancelar orden');
        }
    }

    /**
     * Obtener platos disponibles para el modal (AJAX)
     */
    public function getPlatosDisponibles(Request $request)
    {
        $query = Plato::with('categoria')
            ->where('disponible', 1);
        
        if ($request->filled('buscar')) {
            $query->where('nombre', 'LIKE', '%' . $request->buscar . '%');
        }
        
        $platos = $query->orderBy('nombre', 'asc')->get();
        
        // Obtener IDs de platos ya agregados
        $mesaId = $request->input('mesa_id');
        $platosEnOrden = [];
        
        if ($mesaId) {
            $orden = Orden::where('mesa_id', $mesaId)
                ->where('estado', 'abierta')
                ->first();
            
            if ($orden) {
                $platosEnOrden = OrdenPlato::where('orden_id', $orden->id)
                    ->pluck('plato_id')
                    ->toArray();
            }
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

    private function obtenerOrdenMesa($mesaId)
    {
        $sessionKey = 'orden_mesa_' . $mesaId;
        return Session::get($sessionKey, []);
    }

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
                $query->whereRaw("TIME(hora_reserva) >= ?", [$ahora->format('H:i:s')]);
            })
            ->orderBy('hora_reserva')
            ->first();
    }

    /**
     * ✅ NUEVO: Obtener reserva activa con platos pre-ordenados
     */
    private function obtenerReservaActivaConPlatos(Mesa $mesa)
    {
        $ahora = Carbon::now();
        $hoy = Carbon::today();
        
        return $mesa->reservas()
            ->with('platos')
            ->whereDate('fecha_reserva', $hoy)
            ->whereIn('estado', ['confirmada', 'completada'])
            ->get()
            ->first(function($reserva) use ($ahora) {
                $horaReserva = Carbon::parse($reserva->fecha_reserva->toDateString() . ' ' . $reserva->hora_reserva);
                $inicioVentana = $horaReserva->copy()->subMinutes(30);
                $finVentana = $horaReserva->copy()->addHours(3);
                
                return $ahora->between($inicioVentana, $finVentana) && $reserva->platos->isNotEmpty();
            });
    }

    /**
     * ✅ NUEVO: Cargar platos de la reserva en la sesión de orden
     */
    private function cargarPlatosDeReserva(Mesa $mesa, Reserva $reserva)
    {
        $sessionKey = 'orden_mesa_' . $mesa->id;
        
        $platosOrden = [];
        
        foreach ($reserva->platos as $plato) {
            $platosOrden[$plato->idPlatoProducto] = [
                'id' => $plato->idPlatoProducto,
                'nombre' => $plato->nombre,
                'precio' => $plato->pivot->precio, // Precio guardado en la reserva
                'cantidad' => $plato->pivot->cantidad,
                'nota' => $plato->pivot->notas ?? '', // Notas de la reserva
                'de_reserva' => true, // Marcar que viene de reserva
            ];
        }
        
        Session::put($sessionKey, [
            'mesa_id' => $mesa->id,
            'platos' => $platosOrden,
            'fecha_apertura' => now(),
            'es_reserva' => true,
            'reserva_id' => $reserva->id,
        ]);
    }

    /**
     * ✅ NUEVO: Verificar si una mesa ocupada lo está por una reserva
     */
    private function mesaOcupadaPorReserva(Mesa $mesa)
    {
        if ($mesa->estado !== 'ocupada') {
            return false;
        }
        
        $orden = Orden::where('mesa_id', $mesa->id)
            ->where('estado', 'abierta')
            ->first();
        
        if (!$orden) {
            return false;
        }
        
        $reservaActiva = $this->obtenerReservaActivaConPlatos($mesa);
        return $reservaActiva !== null;
    }
}