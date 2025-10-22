<?php
namespace App\Http\Controllers\Compras;

use App\Http\Controllers\Controller;
use App\Models\Compras\DetalleCompra;
use App\Models\Compras\Compra;
use App\Models\Inventario\Ingrediente;
use App\Models\Inventario\MovimientoInventario;
use App\Models\Inventario\IngredienteLote;
use Illuminate\Http\Request;

class DetalleCompraController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'idCompra' => 'required|exists:compras,idCompra',
            'idIngrediente' => 'required|exists:ingredientes,id',
            'cantidad' => 'required|numeric|min:0.01',
            'precio_unitario' => 'required|numeric|min:0.01',
        ]);
        $detalle = DetalleCompra::create($request->all());
        // Registrar lote si se envía
        if ($request->filled('lote')) {
            IngredienteLote::create([
                'ingrediente_id' => $request->idIngrediente,
                'idDetalleCompra' => $detalle->idDetalleCompra,
                'lote' => $request->lote,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'cantidad' => $request->cantidad,
            ]);
        }
        return back()->with('success', 'Detalle registrado');
    }

    public function update(Request $request, $id)
    {
        $detalle = DetalleCompra::findOrFail($id);
        $request->validate([
            'cantidad_recibida' => 'required|numeric|min:0',
        ]);
        $detalle->cantidad_recibida = $request->cantidad_recibida;
        $detalle->save();

        // Actualizar stock del ingrediente
        $ingrediente = Ingrediente::find($detalle->idIngrediente);
        if ($ingrediente) {
            $ingrediente->stock += $detalle->cantidad_recibida;
            $ingrediente->save();

            MovimientoInventario::create([
                'ingrediente_id' => $ingrediente->id,
                'tipo' => 'entrada',
                'cantidad' => $detalle->cantidad_recibida,
                'motivo' => 'Recepción parcial compra ID ' . $detalle->idCompra,
            ]);
        }
        return back()->with('success', 'Cantidad recibida actualizada y stock ajustado');
    }

    public function destroy($id)
    {
        DetalleCompra::destroy($id);
        return back()->with('success', 'Detalle eliminado');
    }
}
