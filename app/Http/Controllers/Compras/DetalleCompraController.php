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
        $data = $request->validate([
            'idCompra' => 'required|exists:compra,idCompra',
            'idIngrediente' => 'required|exists:ingredientes,id',
            'cantidad' => 'required|numeric|min:0.01',
            'precio_unitario' => 'required|numeric|min:0.01',
        ]);
        
        // MySQL calcula subtotal automáticamente (columna generada)
        $detalle = DetalleCompra::create([
            'idCompra' => $data['idCompra'],
            'idIngrediente' => $data['idIngrediente'],
            'cantidad' => $data['cantidad'],
            'cantidad_recibida' => 0,
            'precio_unitario' => $data['precio_unitario'],
        ]);
        
        // Registrar lote si se envía
        if ($request->filled('lote') && $request->filled('fecha_vencimiento') && $request->filled('cantidad_lote')) {
            IngredienteLote::create([
                'idIngrediente' => $data['idIngrediente'],
                'idDetalleCompra' => $detalle->idDetalleCompra,
                'lote' => $request->lote,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'cantidad' => $request->cantidad_lote,
            ]);
        }
        
        // Recalcular total de la compra
        $compra = Compra::find($data['idCompra']);
        if ($compra) {
            $compra->total = $compra->detalles()->sum('subtotal');
            $compra->save();
        }
        
        return back()->with('success', 'Detalle registrado');
    }

    public function update(Request $request, $id)
    {
        $detalle = DetalleCompra::findOrFail($id);
        $data = $request->validate([
            'cantidad_recibida' => 'required|numeric|min:0',
        ]);
        
        $detalle->cantidad_recibida = $data['cantidad_recibida'];
        $detalle->save();

        // Actualizar stock del ingrediente
        $ingrediente = Ingrediente::find($detalle->idIngrediente);
        if ($ingrediente) {
            $ingrediente->stock += $data['cantidad_recibida'];
            $ingrediente->save();

            MovimientoInventario::create([
                'idIngrediente' => $ingrediente->idIngrediente,
                'tipo' => 'entrada',
                'cantidad' => $data['cantidad_recibida'],
                'motivo' => 'Recepción parcial compra #' . $detalle->idCompra,
            ]);
        }
        
        return back()->with('success', 'Cantidad recibida actualizada y stock ajustado');
    }

    public function destroy($id)
    {
        $detalle = DetalleCompra::findOrFail($id);
        $idCompra = $detalle->idCompra;
        $detalle->delete();
        
        // Recalcular total de la compra
        $compra = Compra::find($idCompra);
        if ($compra) {
            $compra->total = $compra->detalles()->sum('subtotal');
            $compra->save();
        }
        
        return back()->with('success', 'Detalle eliminado');
    }
}
