<?php

namespace App\Http\Controllers;

use App\Models\Tarifa;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class TarifaController extends Controller
{
    public function index(Request $request)
    {
        $query = Tarifa::query();

        if ($request->filled('buscar')) {
            $query->where('tipo_acta', 'like', '%' . $request->buscar . '%');
        }

        $tarifas = $query->orderByDesc('id_tarifa')->paginate(10);

        return view('tarifas.index', compact('tarifas'));
    }

    public function create()
    {
        $tiposRegistrados = Tarifa::pluck('tipo_acta')->toArray();

        $tiposDisponibles = collect([
            'acta_nacimiento' => 'Acta de Nacimiento',
            'acta_matrimonio' => 'Acta de Matrimonio',
            'acta_defuncion'  => 'Acta de Defunción',
        ])->reject(fn($label, $key) => in_array($key, $tiposRegistrados));

        return view('tarifas.create', compact('tiposDisponibles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_acta'      => 'required|in:acta_nacimiento,acta_matrimonio,acta_defuncion',
            'monto'          => 'required|numeric|min:0',
            'vigente_desde'  => 'required|date',
            'vigente_hasta'  => 'nullable|date|after_or_equal:vigente_desde',
        ]);

        try {
            if (Tarifa::where('tipo_acta', $request->tipo_acta)->exists()) {
                throw ValidationException::withMessages([
                    'tipo_acta' => 'Ya existe una tarifa registrada para este tipo de acta.',
                ]);
            }

            Tarifa::create($request->all());

            return redirect()->route('tarifas.index')->with('success', 'Tarifa registrada correctamente.');

        } catch (ValidationException $e) {
            throw $e;

        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Error al guardar la tarifa. Intenta nuevamente.']);
        }
    }

    public function edit(Tarifa $tarifa)
    {
        return view('tarifas.edit', compact('tarifa'));
    }

    public function update(Request $request, Tarifa $tarifa)
    {
        $request->validate([
            'tipo_acta'      => 'required|in:acta_nacimiento,acta_matrimonio,acta_defuncion',
            'monto'          => 'required|numeric|min:0',
            'vigente_desde'  => 'required|date',
            'vigente_hasta'  => 'nullable|date|after_or_equal:vigente_desde',
        ]);

        try {
            if (
                $tarifa->tipo_acta !== $request->tipo_acta &&
                Tarifa::where('tipo_acta', $request->tipo_acta)->exists()
            ) {
                throw ValidationException::withMessages([
                    'tipo_acta' => 'Ya existe otra tarifa registrada con ese tipo de acta.',
                ]);
            }

            $tarifa->update($request->all());

            return redirect()->route('tarifas.index')->with('success', 'Tarifa actualizada correctamente.');

        } catch (ValidationException $e) {
            throw $e;

        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Error al actualizar la tarifa. Intenta nuevamente.']);
        }
    }

    public function destroy(Tarifa $tarifa)
    {
        try {
            $tarifa->delete();

            return redirect()->route('tarifas.index')->with('success', 'Tarifa eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('tarifas.index')->withErrors(['error' => 'Error al eliminar la tarifa.']);
        }
    }

    public function confirmar($id)
    {
        $tarifa = Tarifa::findOrFail($id);
        return view('tarifas.confirmar', compact('tarifa'));
    }
}
