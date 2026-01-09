<?php

namespace App\Http\Controllers;

use App\Models\DeliveryPedido;
use App\Models\DeliveryPedidoPlato;
use App\Models\Plato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    // 📋 Mostrar formulario para hacer pedido
    public function create()
    {
        $platos = Plato::where('disponible', true)->get();
        return view('delivery.create', compact('platos'));
    }

    // 💾 Guardar pedido (sin pago aún)
    public function store(Request $request)
    {
        $request->validate([
            'nombre_cliente' => 'required|string|max:255',
            'email' => 'required|email',
            'telefono' => 'required|string|max:20',
            'direccion_entrega' => 'required|string',
            'referencia' => 'nullable|string',
            'fecha_pedido' => 'required|date',
            'hora_pedido' => 'required',
            'platos' => 'required|array|min:1',
            'platos.*.id' => 'required|integer|exists:platos_productos,idPlatoProducto',
            'platos.*.cantidad' => 'required|integer|min:1',
            'platos.*.notas' => 'nullable|string',
        ]);

        // Crear el pedido
        $pedido = DeliveryPedido::create([
            'idCliente' => Auth::id(),
            'nombre_cliente' => $request->nombre_cliente,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion_entrega' => $request->direccion_entrega,
            'referencia' => $request->referencia,
            'fecha_pedido' => $request->fecha_pedido,
            'hora_pedido' => $request->hora_pedido,
            'comentarios' => $request->comentarios,
            'estado' => 'pendiente',
        ]);

        // Agregar platos al pedido
        $total = 0;
        foreach ($request->platos as $platoData) {
            $plato = Plato::find($platoData['id']);
            
            DeliveryPedidoPlato::create([
                'delivery_pedido_id' => $pedido->id,
                'plato_id' => $plato->idPlatoProducto,
                'cantidad' => $platoData['cantidad'],
                'precio' => $plato->precio,
                'notas' => $platoData['notas'] ?? null,
                'estado' => 'pendiente',
            ]);

            $total += $plato->precio * $platoData['cantidad'];
        }

        // Redirigir a página de pago
        return redirect()->route('delivery.pago', $pedido->id)
            ->with('success', 'Pedido creado. Por favor, completa el pago.')
            ->with('total', $total);
    }

    // 🔍 Consultar estado del pedido
    public function consultarEstado()
    {
        return view('delivery.consultar');
    }

    // 📊 Buscar pedido por email o ID
    public function buscarPedido(Request $request)
    {
        $request->validate([
            'busqueda' => 'required|string',
        ]);

        $pedido = DeliveryPedido::where('email', $request->busqueda)
            ->orWhere('id', $request->busqueda)
            ->with(['platos.plato', 'pago'])
            ->first();

        if (!$pedido) {
            return back()->with('error', 'No se encontró el pedido.');
        }

        return view('delivery.estado', compact('pedido'));
    }

    // 👁️ Ver detalle del pedido
    public function show($id)
    {
        $pedido = DeliveryPedido::with(['platos.plato', 'pago'])->findOrFail($id);
        return view('delivery.show', compact('pedido'));
    }
}   