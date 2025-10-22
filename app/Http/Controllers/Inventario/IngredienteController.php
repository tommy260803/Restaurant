<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Inventario\Ingrediente;
use App\Models\Inventario\MovimientoInventario;
use Illuminate\Http\Request;

class IngredienteController extends Controller
{
    public function index()
    {
        $ingredientes = Ingrediente::where('estado', 'activo')
            ->with('lotes', 'almacenes')
            ->paginate(20);

        return view('ingredientes.index', compact('ingredientes'));
    }

    public function create()
    {
        return view('ingredientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'unidad' => 'required|string|max:20',
            'stock' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'costo_promedio' => 'required|numeric|min:0',
        ]);

        $ingrediente = Ingrediente::create($request->all());

        // Registrar movimiento inicial si el stock > 0
        if ($ingrediente->stock > 0) {
            MovimientoInventario::create([
                'ingrediente_id' => $ingrediente->id,
                'tipo' => 'entrada',
                'cantidad' => $ingrediente->stock,
                'motivo' => 'Registro inicial',
            ]);
        }

        return redirect()->route('ingredientes.index')->with('success', 'Ingrediente creado');
    }

    public function show($id)
    {
        $ingrediente = Ingrediente::with('lotes', 'movimientos', 'almacenes')->findOrFail($id);
        return view('ingredientes.show', compact('ingrediente'));
    }

    public function edit($id)
    {
        $ingrediente = Ingrediente::findOrFail($id);
        return view('ingredientes.edit', compact('ingrediente'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'unidad' => 'required|string|max:20',
            'stock' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'costo_promedio' => 'required|numeric|min:0',
        ]);

        $ingrediente = Ingrediente::findOrFail($id);
        $ingrediente->update($request->all());

        return redirect()->route('ingredientes.index')->with('success', 'Ingrediente actualizado');
    }

    public function destroy($id)
    {
        $ingrediente = Ingrediente::findOrFail($id);
        $ingrediente->estado = 'inactivo';
        $ingrediente->save();

        return redirect()->route('ingredientes.index')->with('success', 'Ingrediente desactivado');
    }

    public function ajustarStock(Request $request, $id)
    {
        $request->validate([
            'cantidad' => 'required|numeric|min:0',
            'tipo' => 'required|in:entrada,salida,ajuste',
            'motivo' => 'nullable|string|max:255',
        ]);

        $ingrediente = Ingrediente::findOrFail($id);
        $cantidad = $request->input('cantidad');
        $tipo = $request->input('tipo');
        $motivo = $request->input('motivo');

        // Ajuste de stock según el tipo
        if ($tipo === 'entrada') {
            $ingrediente->stock += $cantidad;
        } elseif ($tipo === 'salida') {
            // Evitar stock negativo
            if ($ingrediente->stock < $cantidad) {
                return back()->with('error', 'No hay suficiente stock para la salida.');
            }
            $ingrediente->stock -= $cantidad;
        } elseif ($tipo === 'ajuste') {
            $ingrediente->stock = $cantidad;
        }

        $ingrediente->save();

        // Registrar movimiento
        MovimientoInventario::create([
            'ingrediente_id' => $ingrediente->id,
            'tipo' => $tipo,
            'cantidad' => $cantidad,
            'motivo' => $motivo,
        ]);

        return redirect()->route('ingredientes.show', $id)->with('success', 'Stock ajustado');
    }

    public function bajos()
    {
        $ingredientes = Ingrediente::activos()
            ->whereColumn('stock', '<', 'stock_minimo')
            ->with('lotes', 'almacenes')
            ->paginate(20);

        return view('ingredientes.index', compact('ingredientes'));
    }
}
