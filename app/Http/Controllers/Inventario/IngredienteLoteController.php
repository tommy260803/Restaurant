<?php
namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Inventario\IngredienteLote;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IngredienteLoteController extends Controller
{
    public function index($ingrediente_id)
    {
        $lotes = IngredienteLote::where('ingrediente_id', $ingrediente_id)->get();
        return view('ingrediente_lotes.index', compact('lotes', 'ingrediente_id'));
    }

    public function create($ingrediente_id)
    {
        return view('ingrediente_lotes.create', compact('ingrediente_id'));
    }

    public function store(Request $request, $ingrediente_id)
    {
        $request->validate([
            'lote' => 'required|string|max:50',
            'fecha_vencimiento' => 'required|date',
            'cantidad' => 'required|numeric|min:0',
        ]);
        IngredienteLote::create([
            'ingrediente_id' => $ingrediente_id,
            'lote' => $request->lote,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'cantidad' => $request->cantidad,
        ]);
        return redirect()->route('ingrediente_lotes.index', $ingrediente_id)->with('success', 'Lote registrado');
    }

    public function edit($id)
    {
        $lote = IngredienteLote::findOrFail($id);
        return view('ingrediente_lotes.edit', compact('lote'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'lote' => 'required|string|max:50',
            'fecha_vencimiento' => 'required|date',
            'cantidad' => 'required|numeric|min:0',
        ]);
        $lote = IngredienteLote::findOrFail($id);
        $lote->update($request->all());
        return redirect()->route('ingrediente_lotes.index', $lote->ingrediente_id)->with('success', 'Lote actualizado');
    }

    public function destroy($id)
    {
        $lote = IngredienteLote::findOrFail($id);
        $ingrediente_id = $lote->ingrediente_id;
        $lote->delete();
        return redirect()->route('ingrediente_lotes.index', $ingrediente_id)->with('success', 'Lote eliminado');
    }

    public function vencidos($ingrediente_id)
    {
        $hoy = Carbon::today()->toDateString();
        $lotes = IngredienteLote::where('ingrediente_id', $ingrediente_id)
            ->where('fecha_vencimiento', '<', $hoy)
            ->get();
        return view('ingrediente_lotes.vencidos', compact('lotes', 'ingrediente_id'));
    }
}
