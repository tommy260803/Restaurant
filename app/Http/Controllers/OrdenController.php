<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\Plato;
use App\Models\Reserva;
use App\Models\Orden;
use App\Models\OrdenPlato;
use App\Models\Cliente;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrdenController extends Controller
{
    /**
     * Vista principal: Panel de mesas
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
            // ✅ Recargar reservas frescas
            $mesa->load(['reservas' => function($query) use ($hoy) {
                $query->whereDate('fecha_reserva', $hoy)
                    ->whereIn('estado', ['confirmada', 'pendiente', 'completada'])
                    ->with('platos')
                    ->orderBy('hora_reserva');
            }]);

            $mesa->actualizarEstadoSiTieneReservaActiva();

            $mesa->estado_calculado = $mesa->estado_real;
            
            
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
    /**
     * Abrir una mesa (cambiar a ocupada)
     */
    public function abrirMesa(Mesa $mesa)
    {
        $estadoReal = $mesa->estado_real;
        
        if ($estadoReal === 'mantenimiento') {
            return redirect()->back()
                ->with('error', 'Esta mesa está en mantenimiento.');
        }
        
        $tieneOrdenActiva = Orden::where('mesa_id', $mesa->id)
            ->where('estado', 'abierta')
            ->exists();
        
        if ($tieneOrdenActiva) {
            return redirect()->route('ordenes.ver', $mesa->id)
                ->with('info', 'Esta mesa ya tiene una orden activa.');
        }

        DB::beginTransaction();
        try {
            $mesa->update(['estado' => 'ocupada']);
            
            $orden = Orden::create([
                'mesa_id' => $mesa->id,
                'estado' => 'abierta',
                'total' => 0,
                'abierta_por' => Auth::id(),
                'fecha_apertura' => now(),
            ]);
            
            $reservaActiva = $this->obtenerReservaActivaConPlatos($mesa);
            
            if ($reservaActiva && $reservaActiva->platos->isNotEmpty()) {
                foreach ($reservaActiva->platos as $plato) {
                    OrdenPlato::create([
                        'orden_id' => $orden->id,
                        'plato_id' => $plato->idPlatoProducto,
                        'cantidad' => $plato->pivot->cantidad,
                        'precio_unitario' => $plato->pivot->precio,
                        'notas' => $plato->pivot->notas ?? '',
                        'estado_cocina' => 'Enviado a cocina',
                        'es_preorden' => true, // ✅ MARCAR COMO PRE-ORDEN
                    ]);
                }
                
                DB::commit();
                
                return redirect()->route('ordenes.ver', $mesa->id)
                    ->with('success', 'Mesa #' . $mesa->numero . ' abierta con ' . $reservaActiva->platos->count() . ' plato(s) de la reserva cargados automáticamente');
            }
            
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
        
        $orden = Orden::with('platos.plato')
            ->where('mesa_id', $mesa->id)
            ->where('estado', 'abierta')
            ->first();
        
        $reservaActiva = $this->obtenerReservaActiva($mesa);
        
        if ($estadoReal !== 'ocupada' && !$orden && !$reservaActiva) {
            return redirect()->route('ordenes.index')
                ->with('error', 'Esta mesa no tiene una orden activa');
        }
        
        $esReserva = false;
        $reserva = null;
        
        if ($reservaActiva && !$orden) {
            DB::beginTransaction();
            try {
                if ($mesa->estado !== 'ocupada') {
                    $mesa->update(['estado' => 'ocupada']);
                }
                
                $orden = Orden::create([
                    'mesa_id' => $mesa->id,
                    'estado' => 'abierta',
                    'total' => 0,
                    'abierta_por' => Auth::id(),
                    'fecha_apertura' => now(),
                ]);
                
                // ✅ CORREGIDO: Marcar platos de reserva con es_preorden = true
                if ($reservaActiva->platos->isNotEmpty()) {
                    foreach ($reservaActiva->platos as $plato) {
                        OrdenPlato::create([
                            'orden_id' => $orden->id,
                            'plato_id' => $plato->idPlatoProducto,
                            'cantidad' => $plato->pivot->cantidad,
                            'precio_unitario' => $plato->pivot->precio,
                            'notas' => $plato->pivot->notas ?? '',
                            'estado_cocina' => 'Enviado a cocina',
                            'es_preorden' => true, // ✅ MARCAR COMO PRE-ORDEN
                        ]);
                    }
                }
                
                DB::commit();
                $esReserva = true;
                $reserva = $reservaActiva;
                
                // Recargar orden con platos
                $orden = $orden->fresh(['platos.plato']);
                
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Error al crear orden: ' . $e->getMessage());
            }
        } elseif (!$orden) {
            // Crear orden vacía si no existe y no hay reserva
            DB::beginTransaction();
            try {
                if ($mesa->estado !== 'ocupada') {
                    $mesa->update(['estado' => 'ocupada']);
                }
                
                $orden = Orden::create([
                    'mesa_id' => $mesa->id,
                    'estado' => 'abierta',
                    'total' => 0,
                    'abierta_por' => Auth::id(),
                    'fecha_apertura' => now(),
                ]);
                
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Error al crear orden: ' . $e->getMessage());
            }
        } else {
            // Si ya hay orden, verificar si es de una reserva
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
            // ✅ CORREGIDO: Platos agregados manualmente NO son pre-orden
            $ordenPlato = OrdenPlato::create([
                'orden_id' => $orden->id,
                'plato_id' => $plato->idPlatoProducto,
                'cantidad' => 1,
                'precio_unitario' => $plato->precio,
                'notas' => '',
                'estado_cocina' => 'Enviado a cocina',
                'es_preorden' => false, // ✅ NO ES PRE-ORDEN (agregado después)
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
            // $ordenPlato->update(['notas' => $request->nota ?? '']);
            $ordenPlato->notas = $request->nota ?? '';
            $ordenPlato->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Nota actualizada',
                'nota' => $ordenPlato->notas
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
                ->with('success', 'Orden cobrada exitosamente. Total: S/. ' . number_format($orden->total, 2));
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al cobrar: ' . $e->getMessage());
        }
    }

    /**
     * Procesar pago con datos del cliente (AJAX)
     */
    public function procesarPago(Request $request, Mesa $mesa)
    {
        $request->validate([
            'cliente_id' => 'nullable|integer|exists:cliente,idCliente',
            'metodo' => 'required|in:efectivo,tarjeta,yape,plin,otros',
            'numero_operacion' => 'nullable|string|max:191',
            'monto' => 'required|numeric|min:0',
        ]);

        $orden = Orden::with('platos')
            ->where('mesa_id', $mesa->id)
            ->where('estado', 'abierta')
            ->first();
        
        if (!$orden) {
            return response()->json(['message' => 'No hay orden activa'], 404);
        }

        if ($orden->platos->isEmpty()) {
            return response()->json(['message' => 'La orden no tiene platos'], 400);
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
            
            // Crear registro de pago
            $pago = Pago::create([
                'cliente_id' => $request->cliente_id,
                'orden_id' => $orden->id,
                'metodo' => $request->metodo,
                'numero_operacion' => $request->numero_operacion,
                'monto' => $request->monto,
                'fecha' => now(),
                'estado' => 'confirmado',
            ]);

            // Si hay reserva asociada, completarla
            $reservaActiva = $this->obtenerReservaActivaConPlatos($mesa);
            if ($reservaActiva) {
                $reservaActiva->update(['estado' => 'completada']);
            }
            
            // Liberar mesa
            $mesa->update(['estado' => 'disponible']);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pago procesado exitosamente',
                'pago' => $pago,
            ]);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar pago: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar clientes (AJAX)
     */
    public function buscarClientes(Request $request)
    {
        $buscar = $request->get('buscar', '');
        
        if (strlen($buscar) < 2) {
            return response()->json(['clientes' => []]);
        }

        $clientes = Cliente::where('estado', 'activo')
            ->where(function($query) use ($buscar) {
                $query->where('nombre', 'LIKE', "%$buscar%")
                    ->orWhere('apellidoPaterno', 'LIKE', "%$buscar%")
                    ->orWhere('apellidoMaterno', 'LIKE', "%$buscar%")
                    ->orWhere('telefono', 'LIKE', "%$buscar%")
                    ->orWhere('email', 'LIKE', "%$buscar%");
            })
            ->select('idCliente', 'nombre', 'apellidoPaterno', 'apellidoMaterno', 'telefono', 'email', 'puntos')
            ->limit(10)
            ->get()
            ->map(function($cliente) {
                return [
                    'idCliente' => $cliente->idCliente,
                    'nombre' => $cliente->nombre . ' ' . $cliente->apellidoPaterno . ' ' . $cliente->apellidoMaterno,
                    'telefono' => $cliente->telefono,
                    'email' => $cliente->email,
                    'puntos' => $cliente->puntos,
                ];
            });

        return response()->json(['clientes' => $clientes]);
    }

    /**
     * Crear cliente rápido (AJAX)
     */
    public function crearClienteAjax(Request $request)
    {
        $data = $request->only(['nombre','apellidoPaterno','apellidoMaterno','telefono','email']);

        $validator = \Validator::make($data, [
            'nombre' => 'required|string|max:150',
            'apellidoPaterno' => 'required|string|max:40',
            'apellidoMaterno' => 'nullable|string|max:40',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Datos inválidos', 'errors' => $validator->errors()], 422);
        }

        try {
            $cliente = Cliente::create([
                'nombre' => $data['nombre'],
                'apellidoPaterno' => $data['apellidoPaterno'],
                'apellidoMaterno' => $data['apellidoMaterno'] ?? null,
                'telefono' => $data['telefono'] ?? null,
                'email' => $data['email'] ?? null,
                'puntos' => 0,
                'estado' => 'activo',
            ]);

            return response()->json([
                'success' => true,
                'idCliente' => $cliente->idCliente,
                'nombre' => $cliente->nombre . ' ' . $cliente->apellidoPaterno . ' ' . $cliente->apellidoMaterno,
                'telefono' => $cliente->telefono,
                'email' => $cliente->email,
                'puntos' => $cliente->puntos,
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error creando cliente: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Volver a la vista anterior
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
    // MÉTODOS PRIVADOS AUXILIARES CORREGIDOS
    // ============================================

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

    private function obtenerReservaActiva(Mesa $mesa)
    {
        $ahora = Carbon::now();
        $hoy = Carbon::today();
        
        return $mesa->reservas()
            ->with('platos') // Cargar platos por si los tiene
            ->whereDate('fecha_reserva', $hoy)
            ->whereIn('estado', ['confirmada', 'pendiente','completada'])
            ->get()
            ->first(function($reserva) use ($ahora) {
                $horaReserva = Carbon::parse($reserva->fecha_reserva->toDateString() . ' ' . $reserva->hora_reserva);
                $inicioReserva = $horaReserva->copy();
                $finVentana = $horaReserva->copy()->addHours(3);
                
                return $ahora->between($inicioReserva, $finVentana);
            });
    }

    private function obtenerReservaActivaConPlatos(Mesa $mesa)
    {
        $reservaActiva = $this->obtenerReservaActiva($mesa);
        
        // Solo retornar si tiene platos
        if ($reservaActiva && $reservaActiva->platos->isNotEmpty()) {
            return $reservaActiva;
        }
        
        return null;
    }

    private function mesaOcupadaPorReserva(Mesa $mesa)
    {
        // ✅ USAR obtenerReservaActiva() en lugar de obtenerReservaActivaConPlatos()
        // Porque queremos detectar CUALQUIER reserva, tenga o no platos
        $reservaActiva = $this->obtenerReservaActiva($mesa);
        return $reservaActiva !== null;
    }
}