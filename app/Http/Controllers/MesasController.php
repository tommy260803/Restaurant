<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use Illuminate\Http\Request;

class MesasController extends Controller
{
    // Listado de mesas (Admin)
    public function index()
    {
        $mesas = Mesa::orderBy('numero')->get();
        $estadisticas = [
            'total' => $mesas->count(),
            'disponibles' => $mesas->where('estado', 'disponible')->count(),
            'reservadas' => $mesas->where('estado', 'reservada')->count(),
            'ocupadas' => $mesas->where('estado', 'ocupada')->count(),
            'mantenimiento' => $mesas->where('estado', 'mantenimiento')->count(),
        ];
        return view('mesas.index', compact('mesas', 'estadisticas'));
    }

    // Form crear
    public function create()
    {
        return view('mesas.create');
    }

    // Guardar
    public function store(Request $request)
    {
        $data = $request->validate([
            'numero' => 'required|integer|min:1|unique:mesas,numero',
            'capacidad' => 'required|integer|min:1|max:12',
            'estado' => 'required|in:disponible,reservada,ocupada,mantenimiento',
        ]);

        Mesa::create($data);

        return redirect()->route('mesas.index')->with('success', 'Mesa creada correctamente');
    }

    // Form editar
    public function edit(Mesa $mesa)
    {
        return view('mesas.edit', compact('mesa'));
    }

    // Actualizar
    public function update(Request $request, Mesa $mesa)
    {
        $data = $request->validate([
            'numero' => 'required|integer|min:1|unique:mesas,numero,' . $mesa->id,
            'capacidad' => 'required|integer|min:1|max:12',
            'estado' => 'required|in:disponible,reservada,ocupada,mantenimiento',
        ]);

        $mesa->update($data);

        return redirect()->route('mesas.index')->with('success', 'Mesa actualizada correctamente');
    }

    // Eliminar
    public function destroy(Mesa $mesa)
    {
        $mesa->delete();
        return redirect()->route('mesas.index')->with('success', 'Mesa eliminada');
    }

    // Cambiar estado rápido
    public function cambiarEstado(Request $request, Mesa $mesa)
    {
        $request->validate([
            'estado' => 'required|in:disponible,reservada,ocupada,mantenimiento',
        ]);
        $mesa->update(['estado' => $request->estado]);
        return redirect()->back()->with('success', 'Estado de mesa actualizado');
    }
}
