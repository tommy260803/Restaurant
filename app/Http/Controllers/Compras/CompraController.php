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
        $proveedores = \App\Models\Proveedor::where('estado', 'activo')->orderBy('nombre')->get();
        return view('compras.index', compact('compras', 'proveedores'));
    }

    public function create()
    {
        $proveedores = \App\Models\Proveedor::where('estado', 'activo')->orderBy('nombre')->get();
        $ingredientes = \App\Models\Inventario\Ingrediente::orderBy('nombre')->get();
        return view('compras.create', compact('proveedores', 'ingredientes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'idProveedor' => 'required|exists:proveedor,idProveedor',
            'fecha' => 'required|date',
            'descripcion' => 'required|string|max:500',
            'estado' => 'nullable|in:pendiente,en_transito,recibida,anulada',
        ]);
        
        $compra = Compra::create([
            'idProveedor' => $data['idProveedor'],
            'fecha' => $data['fecha'],
            'descripcion' => $data['descripcion'],
            'total' => 0,
            'estado' => $data['estado'] ?? 'pendiente',
        ]);
        
        return redirect()->route('compras.edit', $compra->idCompra)
            ->with('success', 'Compra creada. Ahora agrega los detalles.');
    }

    public function show($id)
    {
        $compra = Compra::with('proveedor', 'detalles.ingrediente')->findOrFail($id);
        return view('compras.show', compact('compra'));
    }

    public function edit($id)
    {
        $compra = Compra::with('detalles.ingrediente')->findOrFail($id);
        $proveedores = \App\Models\Proveedor::where('estado', 'activo')->orderBy('nombre')->get();
        $ingredientes = \App\Models\Inventario\Ingrediente::orderBy('nombre')->get();
        return view('compras.edit', compact('compra', 'proveedores', 'ingredientes'));
    }

    public function update(Request $request, $id)
    {
        $compra = Compra::findOrFail($id);
        
        $data = $request->validate([
            'idProveedor' => 'required|exists:proveedor,idProveedor',
            'fecha' => 'required|date',
            'descripcion' => 'required|string|max:500',
            'estado' => 'nullable|in:pendiente,en_transito,recibida,anulada',
        ]);
        
        $compra->update([
            'idProveedor' => $data['idProveedor'],
            'fecha' => $data['fecha'],
            'descripcion' => $data['descripcion'],
            'estado' => $data['estado'] ?? $compra->estado,
        ]);
        
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
        $pdf = PDF::loadView('compras.comprobante_pdf', compact('compra'));
        return $pdf->download('compra_' . $compra->idCompra . '.pdf');
    }

    protected function recalcularTotal(Compra $compra)
    {
        $total = $compra->detalles()->sum('subtotal');
        $compra->total = $total;
        $compra->save();
    }
}