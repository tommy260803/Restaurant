<?php

namespace App\Http\Controllers;

use App\Models\DeliveryPedido;
use App\Models\PagoDelivery;
use Illuminate\Http\Request;

class PagoDeliveryController extends Controller
{
    // 💳 Mostrar formulario de pago
    public function create($deliveryId)
    {
        $pedido = DeliveryPedido::with('platos.plato')->findOrFail($deliveryId);
        
        // Calcular total
        $total = $pedido->platos->sum(function($item) {
            return $item->precio * $item->cantidad;
        });

        return view('delivery.pago', compact('pedido', 'total'));
    }

    // 💰 Procesar pago
    public function store(Request $request, $deliveryId)
    {
        $request->validate([
            'metodo' => 'required|in:yape,plin,transferencia,efectivo',
            'numero_operacion' => 'required_unless:metodo,efectivo|string',
            'monto' => 'required|numeric|min:0',
        ]);

        $pedido = DeliveryPedido::findOrFail($deliveryId);

        // Crear el pago
        PagoDelivery::create([
            'delivery_id' => $pedido->id,
            'metodo' => $request->metodo,
            'numero_operacion' => $request->numero_operacion,
            'monto' => $request->monto,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('delivery.consultar')
            ->with('success', 'Pago registrado. Espera la confirmación del restaurante.')
            ->with('pedido_id', $pedido->id);
    }
}