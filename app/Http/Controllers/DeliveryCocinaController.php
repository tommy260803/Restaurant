<?php

namespace App\Http\Controllers;

use App\Models\DeliveryPedido;
use App\Models\DeliveryPedidoPlato;
use Illuminate\Http\Request;

class DeliveryCocinaController extends Controller
{
    // 🍳 Ver pedidos en cocina
    public function index()
    {
        $pedidos = DeliveryPedido::whereIn('estado', ['confirmado', 'en_preparacion'])
            ->with(['platos.plato'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('cocinero.delivery', compact('pedidos'));
    }

    // 🔄 Cambiar estado de un plato específico
    public function cambiarEstadoPlato(Request $request, $platoId)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,en_preparacion,preparado'
        ]);

        $platopedido = DeliveryPedidoPlato::findOrFail($platoId);
        
        $platopedido->update([
            'estado' => $request->estado,
            'en_preparacion_at' => $request->estado === 'en_preparacion' ? now() : $platopedido->en_preparacion_at,
            'preparado_at' => $request->estado === 'preparado' ? now() : null,
        ]);

        // Si todos los platos están preparados, cambiar estado del pedido
        $pedido = $platopedido->pedido;
        $todosPreparados = $pedido->platos()->where('estado', '!=', 'preparado')->count() === 0;

        if ($todosPreparados) {
            $pedido->update(['estado' => 'en_camino']);
        }

        return back()->with('success', 'Estado del plato actualizado.');
    }
}