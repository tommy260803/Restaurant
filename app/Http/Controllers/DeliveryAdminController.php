<?php

namespace App\Http\Controllers;

use App\Models\DeliveryPedido;
use App\Models\PagoDelivery;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Mail\DeliveryConfirmado;


class DeliveryAdminController extends Controller
{
    // 📋 Listar todos los pedidos delivery
    public function index()
    {
        $pedidos = DeliveryPedido::with(['platos.plato', 'pago'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.delivery.index', compact('pedidos'));
    }

    // 👁️ Ver detalle del pedido
    public function show($id)
    {
        $pedido = DeliveryPedido::with(['platos.plato', 'pago'])->findOrFail($id);
        return view('admin.delivery.show', compact('pedido'));
    }

    // ✅ Confirmar pago
    public function confirmarPago($id)
{
    $pedido = DeliveryPedido::with(['pago', 'platos.plato'])->findOrFail($id);

    if ($pedido->pago) {
        $pedido->pago->update(['estado' => 'confirmado']);
        $pedido->update(['estado' => 'confirmado']);

        // 🔥 ENVIAR CORREO
        if ($pedido->email) {
            try {
                Mail::to($pedido->email)
                    ->send(new DeliveryConfirmado($pedido));
            } catch (\Exception $e) {
                \Log::warning(
                    'No se pudo enviar correo delivery ' . $pedido->id . ': ' . $e->getMessage()
                );
            }
        }
    }

    return back()->with('success', 'Pago confirmado y correo enviado al cliente.');
}


    // ❌ Rechazar pago
    public function rechazarPago($id)
    {
        $pedido = DeliveryPedido::with('pago')->findOrFail($id);
        
        if ($pedido->pago) {
            $pedido->pago->update(['estado' => 'rechazado']);
            $pedido->update(['estado' => 'cancelado']);
        }

        return back()->with('error', 'Pago rechazado. Pedido cancelado.');
    }

    // 🔄 Cambiar estado del pedido
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:confirmado,en_preparacion,listo,en_camino,entregado,cancelado'
        ]);

        $pedido = DeliveryPedido::findOrFail($id);
        $pedido->update(['estado' => $request->estado]);

        return back()->with('success', 'Estado del pedido actualizado.');
    }

    // 🗑️ Eliminar pedido
    public function destroy($id)
    {
        $pedido = DeliveryPedido::findOrFail($id);
        $pedido->delete();

        return redirect()->route('admin.delivery.index')
            ->with('success', 'Pedido eliminado correctamente.');
    }
}