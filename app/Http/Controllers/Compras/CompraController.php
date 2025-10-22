<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\Controller;
use App\Models\Compras\Compra;
use App\Models\Compras\DetalleCompra;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    public function index(Request $request)
    {
        $query = Compra::with('proveedor');
        if ($request->filled('proveedor')) {
            $query->where('idProveedor', $request->proveedor);
        }
        if ($request->filled('desde')) {
            $query->where('fecha', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $query->where('fecha', '<=', $request->hasta);
        }
        $compras = $query->orderByDesc('fecha')->paginate(20);
        return view('compras.index', compact('compras'));
    }

    public function create()
    {
        // ...cargar proveedores y ingredientes...
        return view('compras.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'idProveedor' => 'required|exists:proveedores,id',
            'fecha' => 'required|date',
            'descripcion' => 'required|string',
        ]);
        $compra = Compra::create($request->all());
        $compra->total = 0;
        $compra->save();
        return redirect()->route('compras.edit', $compra->idCompra);
    }

    public function show($id)
    {
        $compra = Compra::with('proveedor', 'detalles.ingrediente')->findOrFail($id);
        return view('compras.show', compact('compra'));
    }

    public function edit($id)
    {
        $compra = Compra::with('detalles.ingrediente')->findOrFail($id);
        // ...cargar proveedores y ingredientes...
        return view('compras.edit', compact('compra'));
    }

    public function update(Request $request, $id)
    {
        $compra = Compra::findOrFail($id);
        $compra->update($request->all());
        $this->recalcularTotal($compra);
        return redirect()->route('compras.show', $compra->idCompra)->with('success', 'Compra actualizada');
    }

    public function destroy($id)
    {
        Compra::destroy($id);
        return redirect()->route('compras.index')->with('success', 'Compra eliminada');
    }

    public function cambiarEstado(Request $request, $id)
    {
        $compra = Compra::findOrFail($id);
        $estado = $request->input('estado');
        if (in_array($estado, ['pendiente', 'en_transito', 'recibida', 'anulada'])) {
            $compra->estado = $estado;
            $compra->save();
        }
        return redirect()->route('compras.show', $compra->idCompra);
    }

    public function comprobantePDF($id)
    {
        $compra = Compra::with('proveedor', 'detalles.ingrediente')->findOrFail($id);
        // ...genera PDF con dompdf o similar...
        return view('compras.comprobante_pdf', compact('compra'));
    }

    protected function recalcularTotal(Compra $compra)
    {
        $total = $compra->detalles()->sum('subtotal');
        $compra->total = $total;
        $compra->save();
    }
}