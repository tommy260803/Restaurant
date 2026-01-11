<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\OrdenPlato;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CocineroController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();
        
        // Contar pedidos de RESERVAS
        $pendientesReserva = Pedido::whereHas('reserva', function($q) use ($hoy) {
            $q->whereDate('fecha_reserva', '>=', $hoy);
        })->where('estado', 'Enviado a cocina')->count();
        
        $enPrepReserva = Pedido::whereHas('reserva', function($q) use ($hoy) {
            $q->whereDate('fecha_reserva', '>=', $hoy);
        })->where('estado', 'En preparación')->count();
        
        $preparadosReserva = Pedido::whereHas('reserva', function($q) use ($hoy) {
            $q->whereDate('fecha_reserva', $hoy);
        })->where('estado', 'Preparado')->count();

        // Contar pedidos de ÓRDENES DIRECTAS
        $pendientesOrden = OrdenPlato::whereHas('orden', function($q) {
            $q->where('estado', 'abierta');
        })->where('estado_cocina', 'Enviado a cocina')->count();
        
        $enPrepOrden = OrdenPlato::whereHas('orden', function($q) {
            $q->where('estado', 'abierta');
        })->where('estado_cocina', 'En preparación')->count();
        
        $preparadosOrden = OrdenPlato::whereHas('orden', function($q) use ($hoy) {
            $q->where('estado', 'abierta')
              ->whereDate('fecha_apertura', $hoy);
        })->where('estado_cocina', 'Preparado')->count();

        // Totales combinados
        $pendientes = $pendientesReserva + $pendientesOrden;
        $enPrep = $enPrepReserva + $enPrepOrden;
        $preparados = $preparadosReserva + $preparadosOrden;

        // Obtener pedidos pendientes UNIFICADOS (reservas + órdenes)
        $pedidos = $this->obtenerPedidosUnificados('Enviado a cocina', 10);

        return view('cocinero.index', compact('pendientes', 'enPrep', 'preparados', 'pedidos'));
    }

    public function pedidosPendientes(Request $request)
    {
        $estado = $request->input('estado'); // puede ser null o ''

        $pedidos = $this->obtenerPedidosUnificadosPaginados(
            $estado ?: null,
            20
        );

        return view('cocinero.pedidos', compact('pedidos', 'estado'));

    }

    public function detalle(Request $request, $id)
    {
        $tipo = $request->query('tipo', 'reserva');

        if ($tipo === 'orden') {
            // Obtener el plato de la orden con todas sus relaciones
            $op = OrdenPlato::with(['orden.mesa', 'plato'])->findOrFail($id);

            $nombreCliente = 'Orden Directa';
            if ($op->orden && $op->orden->mesa) {
                $ahora = \Carbon\Carbon::now();
                $reservaActiva = $op->orden->mesa->reservas()
                    ->whereIn('estado', ['confirmada', 'pendiente', 'completada'])
                    ->whereDate('fecha_reserva', $ahora->toDateString())
                    ->get()
                    ->first(function($reserva) use ($ahora) {
                        $horaReserva = \Carbon\Carbon::parse($reserva->fecha_reserva->toDateString() . ' ' . $reserva->hora_reserva);
                        $inicioReserva = $horaReserva->copy();
                        $finVentana = $horaReserva->copy()->addHours(3);
                        return $ahora->between($inicioReserva, $finVentana);
                    });
                
                if ($reservaActiva) {
                    $nombreCliente = $reservaActiva->nombre_cliente ?? 'Orden Directa';
                }
            }

            $pedido = (object)[
                'id' => $op->id,
                'tipo' => 'orden',
                'mesa_numero' => optional($op->orden->mesa)->numero ?? '—',
                'cliente_nombre' => $nombreCliente,
                'personas' => '—',
                'hora_ingreso' => optional($op->orden->fecha_apertura)->format('H:i') ?? '—',
                'estado' => $op->estado_cocina,
                'plato_nombre' => optional($op->plato)->nombre ?? '—',
                'plato_descripcion' => optional($op->plato)->descripcion ?? '',
                'cantidad' => $op->cantidad,
                'precio' => $op->precio_unitario,
                'notas' => $op->notas ?? '',
            ];

        } else {
            // Obtener el pedido de reserva con todas sus relaciones
            $p = Pedido::with(['reserva.mesa', 'plato'])->findOrFail($id);

            $pedido = (object)[
                'id' => $p->id,
                'tipo' => 'reserva',
                'mesa_numero' => optional($p->reserva->mesa)->numero ?? '—',
                'cliente_nombre' => optional($p->reserva)->nombre_cliente ?? '—',
                'personas' => optional($p->reserva)->numero_personas ?? '—',
                'hora_ingreso' => optional($p->created_at)->format('H:i') ?? '—',
                'estado' => $p->estado,
                'plato_nombre' => optional($p->plato)->nombre ?? '—',
                'plato_descripcion' => optional($p->plato)->descripcion ?? '',
                'cantidad' => $p->cantidad,
                'precio' => $p->precio,
                'notas' => $p->notas ?? '',
            ];
        }

        return view('cocinero.detalle', compact('pedido'));
    }




    public function marcarPreparacion($id)
    {
        try {
            $tipo = request()->input('tipo', 'reserva');
            
            if ($tipo === 'orden') {
                $pedido = OrdenPlato::findOrFail($id);
                $pedido->update([
                    'estado_cocina' => 'En preparación',
                    'en_preparacion_at' => now(),
                ]);
            } else {
                $pedido = Pedido::findOrFail($id);
                $pedido->update([
                    'estado' => 'En preparación',
                    'en_preparacion_at' => now(),
                ]);
            }
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Pedido marcado como En preparación',
                    'pedido' => [
                        'id' => $pedido->id,
                        'estado' => $tipo === 'orden' ? $pedido->estado_cocina : $pedido->estado,
                        'tipo' => $tipo
                    ]
                ]);
            }
            
            return back()->with('success', 'Pedido marcado como En preparación');
            
        } catch (\Exception $e) {
            Log::error('Error en marcarPreparacion: ' . $e->getMessage());
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el pedido'
                ], 500);
            }
            
            return back()->with('error', 'Error al actualizar el pedido');
        }
    }

    public function marcarPreparado($id)
    {
        try {
            $tipo = request()->input('tipo', 'reserva');
            
            if ($tipo === 'orden') {
                $pedido = OrdenPlato::findOrFail($id);
                $pedido->update([
                    'estado_cocina' => 'Preparado',
                    'preparado_at' => now(),
                ]);
            } else {
                $pedido = Pedido::findOrFail($id);
                $pedido->update([
                    'estado' => 'Preparado',
                    'preparado_at' => now(),
                ]);
            }
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pedido marcado como Preparado',
                    'pedido' => [
                        'id' => $pedido->id,
                        'estado' => $tipo === 'orden' ? $pedido->estado_cocina : $pedido->estado,
                        'tipo' => $tipo
                    ]
                ]);
            }
            
            return back()->with('success', 'Pedido marcado como Preparado');
            
        } catch (\Exception $e) {
            Log::error('Error en marcarPreparado: ' . $e->getMessage());
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el pedido'
                ], 500);
            }
            
            return back()->with('error', 'Error al actualizar el pedido');
        }
    }

    public function registrarIncidencia(Request $request, $id)
    {
        $data = $request->validate([
            'notas' => 'required|string|max:1000',
            'tipo' => 'nullable|string|in:reserva,orden'
        ]);
        
        $tipo = $data['tipo'] ?? 'reserva';
        
        if ($tipo === 'orden') {
            $pedido = OrdenPlato::findOrFail($id);
        } else {
            $pedido = Pedido::findOrFail($id);
        }
        
        $pedido->update(['notas' => $data['notas']]);
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Incidencia registrada']);
        }
        
        return back()->with('success', 'Incidencia registrada correctamente');
    }

    public function historial(Request $request)
    {
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        // ===== RESERVAS =====
        $queryReserva = Pedido::with(['reserva.mesa', 'plato'])
            ->where('estado', 'Preparado');

        if ($desde) {
            $queryReserva->whereDate('preparado_at', '>=', $desde);
        }
        if ($hasta) {
            $queryReserva->whereDate('preparado_at', '<=', $hasta);
        }

        $pedidosReserva = $queryReserva->get()->map(function ($p) {
            $p->tipo = 'reserva';
            return $p;
        });

        // ===== ÓRDENES DIRECTAS =====
        $queryOrden = OrdenPlato::with(['orden.mesa', 'plato'])
            ->where('estado_cocina', 'Preparado');

        if ($desde) {
            $queryOrden->whereDate('preparado_at', '>=', $desde);
        }
        if ($hasta) {
            $queryOrden->whereDate('preparado_at', '<=', $hasta);
        }

        $pedidosOrden = $queryOrden->get()->map(function ($p) {
            $p->tipo = 'orden';
            return $p;
        });

        // ===== UNIFICAR =====
        $todos = $pedidosReserva
            ->concat($pedidosOrden)
            ->sortByDesc('preparado_at')
            ->values();

        // ===== PAGINACIÓN MANUAL =====
        $perPage = 10;
        $page = request()->get('page', 1);
        $offset = ($page - 1) * $perPage;

        $pedidos = new \Illuminate\Pagination\LengthAwarePaginator(
            $todos->slice($offset, $perPage),
            $todos->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query()
            ]
        );

        return view('cocinero.historial', compact('pedidos', 'desde', 'hasta'));
    }


    // ====== API ligera para auto-actualización ======
    public function stats()
    {
        $today = Carbon::today();
        
        // Reservas
        $baseReserva = Pedido::query()->whereHas('reserva', function($q) use ($today) {
            $q->whereDate('fecha_reserva', '>=', $today);
        });
        
        $pendientesReserva = (clone $baseReserva)->where('estado', 'Enviado a cocina')->count();
        $enPrepReserva = (clone $baseReserva)->where('estado', 'En preparación')->count();
        $preparadosReserva = (clone $baseReserva)->whereDate('preparado_at', $today)->where('estado', 'Preparado')->count();
        
        // Órdenes directas
        $pendientesOrden = OrdenPlato::whereHas('orden', function($q) {
            $q->where('estado', 'abierta');
        })->where('estado_cocina', 'Enviado a cocina')->count();
        
        $enPrepOrden = OrdenPlato::whereHas('orden', function($q) {
            $q->where('estado', 'abierta');
        })->where('estado_cocina', 'En preparación')->count();
        
        $preparadosOrden = OrdenPlato::whereHas('orden', function($q) use ($today) {
            $q->where('estado', 'abierta')->whereDate('fecha_apertura', $today);
        })->where('estado_cocina', 'Preparado')->count();
        
        return response()->json([
            'pendientes' => $pendientesReserva + $pendientesOrden,
            'en_preparacion' => $enPrepReserva + $enPrepOrden,
            'preparados' => $preparadosReserva + $preparadosOrden,
        ]);
    }

    public function pendientesRecientes()
    {
        $pedidos = $this->obtenerPedidosUnificados(['Enviado a cocina', 'En preparación'], 10);
        return response()->json(['data' => $pedidos]);
    }

    public function detalleJson($id)
    {
        $tipo = request()->input('tipo', 'reserva');
        
        if ($tipo === 'orden') {
            $pedido = OrdenPlato::with(['orden.mesa', 'plato'])->findOrFail($id);
            
            // ✅ CORRECCIÓN: Obtener nombre del cliente si hay reserva activa
            $nombreCliente = 'Orden Directa';
            if ($pedido->orden && $pedido->orden->mesa) {
                $ahora = \Carbon\Carbon::now();
                $reservaActiva = $pedido->orden->mesa->reservas()
                    ->whereIn('estado', ['confirmada', 'pendiente', 'completada'])
                    ->whereDate('fecha_reserva', $ahora->toDateString())
                    ->get()
                    ->first(function($reserva) use ($ahora) {
                        $horaReserva = \Carbon\Carbon::parse($reserva->fecha_reserva->toDateString() . ' ' . $reserva->hora_reserva);
                        $inicioReserva = $horaReserva->copy();
                        $finVentana = $horaReserva->copy()->addHours(3);
                        return $ahora->between($inicioReserva, $finVentana);
                    });
                
                if ($reservaActiva) {
                    $nombreCliente = $reservaActiva->nombre_cliente ?? 'Orden Directa';
                }
            }

            return response()->json([
                'id' => $pedido->id,
                'tipo' => 'orden',
                'estado' => $pedido->estado_cocina,
                'created_at' => optional($pedido->created_at)->toDateTimeString(),
                'en_preparacion_at' => optional($pedido->en_preparacion_at)->toDateTimeString(),
                'preparado_at' => optional($pedido->preparado_at)->toDateTimeString(),
                'notas' => $pedido->notas ?? '',
                'cantidad' => $pedido->cantidad,
                'precio' => $pedido->precio_unitario,
                'plato' => [
                    'nombre' => optional($pedido->plato)->nombre ?? '—',
                    'descripcion' => optional($pedido->plato)->descripcion ?? '',
                ],
                'reserva' => [
                    'cliente' => $nombreCliente,
                    'personas' => '—',
                    'mesa' => optional($pedido->orden->mesa)->numero ?? '—',
                    'hora' => optional($pedido->orden->fecha_apertura)->format('H:i') ?? '—',
                ],
            ]);
        } else {
            $pedido = Pedido::with(['reserva.mesa', 'plato'])->findOrFail($id);
            
            return response()->json([
                'id' => $pedido->id,
                'tipo' => 'reserva',
                'estado' => $pedido->estado,
                'created_at' => optional($pedido->created_at)->toDateTimeString(),
                'en_preparacion_at' => optional($pedido->en_preparacion_at)->toDateTimeString(),
                'preparado_at' => optional($pedido->preparado_at)->toDateTimeString(),
                'notas' => $pedido->notas ?? '',
                'cantidad' => $pedido->cantidad,
                'precio' => $pedido->precio,
                'plato' => [
                    'nombre' => optional($pedido->plato)->nombre ?? '—',
                    'descripcion' => optional($pedido->plato)->descripcion ?? '',
                ],
                'reserva' => [
                    'cliente' => optional($pedido->reserva)->nombre_cliente ?? '—',
                    'personas' => optional($pedido->reserva)->numero_personas ?? '—',
                    'mesa' => optional(optional($pedido->reserva)->mesa)->numero ?? '—',
                    'hora' => optional($pedido->reserva)->hora_reserva ?? '—',
                ],
            ]);
        }
    }

    // ============================================
    // MÉTODOS AUXILIARES PRIVADOS
    // ============================================

    /**
     * Obtener pedidos unificados (reservas + órdenes) con límite
     */
    private function obtenerPedidosUnificados($estados, $limit = 10)
    {
        if (!is_array($estados)) {
            $estados = [$estados];
        }

        $hoy = Carbon::today();
        
        // Pedidos de reservas
        $pedidosReserva = Pedido::with(['reserva.mesa', 'plato'])
            ->whereHas('reserva', function($q) use ($hoy) {
                $q->whereDate('fecha_reserva', '>=', $hoy);
            })
            ->whereIn('estado', $estados)
            ->orderBy('created_at')
            ->get()
            ->map(function($p){
                return [
                    'id' => $p->id,
                    'tipo' => 'reserva',
                    'estado' => $p->estado,
                    'hora' => optional($p->created_at)->format('H:i'),
                    'mesa' => optional(optional($p->reserva)->mesa)->numero,
                    'cliente' => optional($p->reserva)->nombre_cliente,
                    'plato' => optional($p->plato)->nombre,
                    'cantidad' => $p->cantidad,
                    'notas' => $p->notas,
                    'created_at' => $p->created_at,
                ];
            });

        // Pedidos de órdenes directas
        $estadosCocina = array_map(function($e) {
            return $e; // Ya vienen en el formato correcto
        }, $estados);

        $pedidosOrden = OrdenPlato::with(['orden.mesa', 'plato'])
            ->whereHas('orden', function($q) {
                $q->where('estado', 'abierta');
            })
            ->whereIn('estado_cocina', $estadosCocina)
            ->orderBy('created_at')
            ->get()
            ->map(function($p){
            // ✅ CORRECCIÓN: Obtener nombre del cliente si hay reserva activa
            $nombreCliente = 'Orden Directa';
            if ($p->orden && $p->orden->mesa) {
                $ahora = \Carbon\Carbon::now();
                $reservaActiva = $p->orden->mesa->reservas()
                    ->whereIn('estado', ['confirmada', 'pendiente', 'completada'])
                    ->whereDate('fecha_reserva', $ahora->toDateString())
                    ->get()
                    ->first(function($reserva) use ($ahora) {
                        $horaReserva = \Carbon\Carbon::parse($reserva->fecha_reserva->toDateString() . ' ' . $reserva->hora_reserva);
                        $inicioReserva = $horaReserva->copy();
                        $finVentana = $horaReserva->copy()->addHours(3);
                        return $ahora->between($inicioReserva, $finVentana);
                    });
                
                if ($reservaActiva) {
                    $nombreCliente = $reservaActiva->nombre_cliente ?? 'Orden Directa';
                }
            }
            
            return [
                'id' => $p->id,
                'tipo' => 'orden',
                'estado' => $p->estado_cocina,
                'hora' => optional($p->created_at)->format('H:i'),
                'mesa' => optional(optional($p->orden)->mesa)->numero,
                'cliente' => $nombreCliente,
                    'plato' => optional($p->plato)->nombre,
                    'cantidad' => $p->cantidad,
                    'notas' => $p->notas,
                    'created_at' => $p->created_at,
                ];
            });

        // Combinar, ordenar por fecha y limitar
        return $pedidosReserva->concat($pedidosOrden)
            ->sortBy('created_at')
            ->take($limit)
            ->values()
            ->toArray();
    }

    /**
     * Obtener pedidos unificados paginados
     */
    private function obtenerPedidosUnificadosPaginados($estado = null, $perPage = 20)
    {
        $hoy = Carbon::today();

        // ===== RESERVAS =====
        $queryReserva = Pedido::with(['reserva.mesa', 'plato'])
            ->whereHas('reserva', function($q) use ($hoy) {
                $q->whereDate('fecha_reserva', '>=', $hoy);
            });

        if ($estado) {
            $queryReserva->where('estado', $estado);
        }

        $pedidosReserva = $queryReserva
            ->orderBy('created_at')
            ->get()
            ->map(function($p) {
                $p->tipo = 'reserva';
                $p->mesa_numero = optional(optional($p->reserva)->mesa)->numero;
                $p->cliente = optional($p->reserva)->nombre_cliente;
                return $p;
            });

        // ===== ÓRDENES DIRECTAS =====
        $queryOrden = OrdenPlato::with(['orden.mesa', 'plato'])
            ->whereHas('orden', function($q) {
                $q->where('estado', 'abierta');
            });

        if ($estado) {
            $queryOrden->where('estado_cocina', $estado);
        }

        $pedidosOrden = $queryOrden
        ->orderBy('created_at')
        ->get()
        ->map(function($p) {
            $p->tipo = 'orden';
            $p->mesa_numero = optional(optional($p->orden)->mesa)->numero;
            
            // ✅ CORRECCIÓN: Obtener nombre del cliente si hay reserva activa
            $nombreCliente = 'Orden Directa';
            if ($p->orden && $p->orden->mesa) {
                $ahora = \Carbon\Carbon::now();
                $reservaActiva = $p->orden->mesa->reservas()
                    ->whereIn('estado', ['confirmada', 'pendiente', 'completada'])
                    ->whereDate('fecha_reserva', $ahora->toDateString())
                    ->get()
                    ->first(function($reserva) use ($ahora) {
                        $horaReserva = \Carbon\Carbon::parse($reserva->fecha_reserva->toDateString() . ' ' . $reserva->hora_reserva);
                        $inicioReserva = $horaReserva->copy();
                        $finVentana = $horaReserva->copy()->addHours(3);
                        return $ahora->between($inicioReserva, $finVentana);
                    });
                
                if ($reservaActiva) {
                    $nombreCliente = $reservaActiva->nombre_cliente ?? 'Orden Directa';
                }
            }
            
            $p->cliente = $nombreCliente; // ✅ NOMBRE DEL CLIENTE
            $p->estado = $p->estado_cocina;
            return $p;
        });

        // ===== COMBINAR =====
        $todosPedidos = $pedidosReserva
            ->concat($pedidosOrden)
            ->sortByDesc('created_at')
            ->values();

        // ===== PAGINACIÓN MANUAL =====
        $page = request()->get('page', 1);
        $offset = ($page - 1) * $perPage;

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $todosPedidos->slice($offset, $perPage),
            $todosPedidos->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

}