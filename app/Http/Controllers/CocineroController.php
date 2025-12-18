<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CocineroController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();
        
        // Contar pedidos de hoy en adelante (incluyendo futuras)
        $pendientes = Pedido::whereHas('reserva', function($q) use ($hoy) {
            $q->whereDate('fecha_reserva', '>=', $hoy);
        })->where('estado', 'Enviado a cocina')->count();
        
        $enPrep = Pedido::whereHas('reserva', function($q) use ($hoy) {
            $q->whereDate('fecha_reserva', '>=', $hoy);
        })->where('estado', 'En preparación')->count();
        
        $preparados = Pedido::whereHas('reserva', function($q) use ($hoy) {
            $q->whereDate('fecha_reserva', $hoy);
        })->where('estado', 'Preparado')->count();

        // Primeros pedidos pendientes
        $pedidos = Pedido::with(['reserva.mesa', 'plato'])
            ->whereHas('reserva', function($q) use ($hoy) {
                $q->whereDate('fecha_reserva', '>=', $hoy);
            })
            ->where('estado', 'Enviado a cocina')
            ->orderBy('created_at')
            ->limit(10)
            ->get();

        return view('cocinero.index', compact('pendientes', 'enPrep', 'preparados', 'pedidos'));
    }

    public function pedidosPendientes(Request $request)
    {
        $estado = $request->input('estado', 'Enviado a cocina');
        $hoy = Carbon::today();

        $query = Pedido::with(['reserva.mesa', 'plato'])
            ->whereHas('reserva', function($q) use ($hoy) {
                $q->whereDate('fecha_reserva', '>=', $hoy);
            })
            ->orderBy('created_at');

        if ($estado) {
            $query->where('estado', $estado);
        }

        $pedidos = $query->paginate(20)->appends($request->query());

        return view('cocinero.pedidos', compact('pedidos', 'estado'));
    }

    public function detalle($id)
    {
        $pedido = Pedido::with(['reserva.mesa', 'plato'])
            ->findOrFail($id);
        return view('cocinero.detalle', compact('pedido'));
    }

    public function marcarPreparacion($id)
    {
        try {
            $pedido = Pedido::findOrFail($id);
            
            $pedido->update([
                'estado' => 'En preparación',
                'en_preparacion_at' => now(),
            ]);
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Pedido marcado como En preparación',
                    'pedido' => [
                        'id' => $pedido->id,
                        'estado' => $pedido->estado
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
            $pedido = Pedido::findOrFail($id);
            
            $pedido->update([
                'estado' => 'Preparado',
                'preparado_at' => now(),
            ]);
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pedido marcado como Preparado',
                    'pedido' => [
                        'id' => $pedido->id,
                        'estado' => $pedido->estado
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
        ]);
        
        $pedido = Pedido::findOrFail($id);
        $pedido->update([
            'notas' => $data['notas'],
        ]);
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Incidencia registrada']);
        }
        
        return back()->with('success', 'Incidencia registrada');
    }

    public function historial(Request $request)
    {
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        $query = Pedido::with(['reserva.mesa', 'plato'])
            ->where('estado', 'Preparado')
            ->orderBy('preparado_at', 'desc');

        if (!empty($desde)) {
            $query->whereDate('preparado_at', '>=', $desde);
        }
        if (!empty($hasta)) {
            $query->whereDate('preparado_at', '<=', $hasta);
        }

        $pedidos = $query->paginate(20)->appends($request->query());

        return view('cocinero.historial', compact('pedidos', 'desde', 'hasta'));
    }

    // ====== API ligera para auto-actualización ======
    public function stats()
    {
        $today = Carbon::today();
        
        $base = Pedido::query()->whereHas('reserva', function($q) use ($today) {
            $q->whereDate('fecha_reserva', '>=', $today);
        });
        
        return response()->json([
            'pendientes' => (clone $base)->where('estado', 'Enviado a cocina')->count(),
            'en_preparacion' => (clone $base)->where('estado', 'En preparación')->count(),
            'preparados' => (clone $base)->whereDate('preparado_at', $today)->where('estado', 'Preparado')->count(),
        ]);
    }

    public function pendientesRecientes()
    {
        $today = Carbon::today();
        
        $items = Pedido::with(['reserva.mesa', 'plato'])
            ->whereIn('estado', ['Enviado a cocina', 'En preparación'])
            ->whereHas('reserva', function($q) use ($today) { 
                $q->whereDate('fecha_reserva', '>=', $today); 
            })
            ->orderBy('created_at')
            ->limit(10)
            ->get()
            ->map(function($p){
                return [
                    'id' => $p->id,
                    'estado' => $p->estado,
                    'hora' => optional($p->created_at)->format('H:i'),
                    'mesa' => optional(optional($p->reserva)->mesa)->numero,
                    'cliente' => optional($p->reserva)->nombre_cliente,
                    'plato' => optional($p->plato)->nombre,
                    'cantidad' => $p->cantidad,
                    'notas' => $p->notas,
                ];
            });

        return response()->json(['data' => $items]);
    }

    public function detalleJson($id)
    {
        $pedido = Pedido::with(['reserva.mesa', 'plato'])->findOrFail($id);
        
        return response()->json([
            'id' => $pedido->id,
            'estado' => $pedido->estado,
            'created_at' => optional($pedido->created_at)->toDateTimeString(),
            'en_preparacion_at' => optional($pedido->en_preparacion_at)->toDateTimeString(),
            'preparado_at' => optional($pedido->preparado_at)->toDateTimeString(),
            'notas' => $pedido->notas,
            'cantidad' => $pedido->cantidad,
            'precio' => $pedido->precio,
            'plato' => [
                'nombre' => optional($pedido->plato)->nombre,
                'descripcion' => optional($pedido->plato)->descripcion,
            ],
            'reserva' => [
                'cliente' => optional($pedido->reserva)->nombre_cliente,
                'personas' => optional($pedido->reserva)->numero_personas,
                'mesa' => optional(optional($pedido->reserva)->mesa)->numero,
                'hora' => optional($pedido->reserva)->hora_reserva,
            ],
        ]);
    }
}