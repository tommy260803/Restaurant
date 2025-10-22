<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Inventario\Almacen;
use App\Models\Inventario\Ingrediente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlmacenController extends Controller
{
    public function index()
    {
        $almacenes = Almacen::all();
        return view('almacenes.index', compact('almacenes'));
    }

    public function show($id)
    {
        $almacen = Almacen::with('ingredientes')->findOrFail($id);
        return view('almacenes.show', compact('almacen'));
    }

    public function create()
    {
        return view('almacenes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'ubicacion' => 'nullable|string|max:150',
            'responsable' => 'nullable|string|max:100',
        ]);
        Almacen::create($request->all());
        return redirect()->route('almacenes.index')->with('success', 'Almacén creado');
    }

    public function edit($id)
    {
        $almacen = Almacen::findOrFail($id);
        return view('almacenes.edit', compact('almacen'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'ubicacion' => 'nullable|string|max:150',
            'responsable' => 'nullable|string|max:100',
        ]);
        $almacen = Almacen::findOrFail($id);
        $almacen->update($request->all());
        return redirect()->route('almacenes.index')->with('success', 'Almacén actualizado');
    }

    public function destroy($id)
    {
        Almacen::destroy($id);
        return redirect()->route('almacenes.index')->with('success', 'Almacén eliminado');
    }

    public function transferirStock(Request $request)
    {
        $request->validate([
            'origen_id' => 'required|exists:almacenes,id',
            'destino_id' => 'required|exists:almacenes,id|different:origen_id',
            'ingrediente_id' => 'required|exists:ingredientes,id',
            'cantidad' => 'required|numeric|min:0.01',
        ]);
        $origen = $request->origen_id;
        $destino = $request->destino_id;
        $ingrediente_id = $request->ingrediente_id;
        $cantidad = $request->cantidad;

        // Verificar stock suficiente en origen
        $stockOrigen = DB::table('almacen_ingredientes')
            ->where('almacen_id', $origen)
            ->where('ingrediente_id', $ingrediente_id)
            ->value('stock');
        if ($stockOrigen < $cantidad) {
            return back()->with('error', 'No hay suficiente stock en el almacén origen.');
        }

        // Descontar en origen
        DB::table('almacen_ingredientes')
            ->where('almacen_id', $origen)
            ->where('ingrediente_id', $ingrediente_id)
            ->decrement('stock', $cantidad);

        // Sumar en destino
        DB::table('almacen_ingredientes')
            ->where('almacen_id', $destino)
            ->where('ingrediente_id', $ingrediente_id)
            ->increment('stock', $cantidad);

        // Registrar movimientos
        \App\Models\Inventario\MovimientoInventario::create([
            'ingrediente_id' => $ingrediente_id,
            'tipo' => 'salida',
            'cantidad' => $cantidad,
            'motivo' => "Transferencia de almacén $origen a $destino",
        ]);
        \App\Models\Inventario\MovimientoInventario::create([
            'ingrediente_id' => $ingrediente_id,
            'tipo' => 'entrada',
            'cantidad' => $cantidad,
            'motivo' => "Transferencia recibida de almacén $origen",
        ]);

        return back()->with('success', 'Transferencia realizada correctamente');
    }
}