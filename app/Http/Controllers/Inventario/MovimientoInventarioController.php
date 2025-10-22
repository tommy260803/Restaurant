<?php
namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Inventario\MovimientoInventario;
use App\Models\Inventario\Ingrediente;
use Illuminate\Http\Request;

class MovimientoInventarioController extends Controller
{
    public function index(Request $request)
    {
        $query = MovimientoInventario::with('ingrediente')->orderByDesc('fecha');
        if ($request->filled('ingrediente_id')) {
            $query->where('ingrediente_id', $request->ingrediente_id);
        }
        if ($request->filled('desde')) {
            $query->where('fecha', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $query->where('fecha', '<=', $request->hasta);
        }
        $movimientos = $query->paginate(20);
        return view('movimientos-inventario.index', compact('movimientos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ingrediente_id' => 'required|exists:ingredientes,id',
            'tipo' => 'required|in:entrada,salida,ajuste',
            'cantidad' => 'required|numeric|min:0',
            'motivo' => 'nullable|string|max:255',
        ]);
        $ingrediente = Ingrediente::findOrFail($request->ingrediente_id);
        $cantidad = $request->cantidad;
        $tipo = $request->tipo;

        if ($tipo === 'entrada') {
            $ingrediente->stock += $cantidad;
        } elseif ($tipo === 'salida') {
            if ($ingrediente->stock < $cantidad) {
                return back()->with('error', 'No hay suficiente stock para la salida.');
            }
            $ingrediente->stock -= $cantidad;
        } else {
            $ingrediente->stock = $cantidad;
        }
        $ingrediente->save();

        MovimientoInventario::create([
            'ingrediente_id' => $ingrediente->id,
            'tipo' => $tipo,
            'cantidad' => $cantidad,
            'motivo' => $request->motivo,
        ]);

        return back()->with('success', 'Movimiento registrado');
    }

    public function show($id)
    {
        $movimiento = MovimientoInventario::with('ingrediente')->findOrFail($id);
        return view('movimientos-inventario.show', compact('movimiento'));
    }
}
