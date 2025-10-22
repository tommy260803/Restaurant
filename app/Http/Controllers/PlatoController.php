<?php

namespace App\Http\Controllers;

use App\Models\Plato;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlatoController extends Controller
{
    /**
     * Mostrar listado de platos
     */
    public function index(Request $request)
    {
        $query = Plato::with('categoria');
        
        // Búsqueda por nombre
        if ($request->filled('buscar')) {
            $query->where('nombre', 'LIKE', '%' . $request->buscar . '%');
        }
        
        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('idCategoria', $request->categoria);
        }
        
        $platos = $query->orderBy('nombre', 'asc')->paginate(10);
        
        // Obtener todas las categorías activas para el filtro
        $categorias = Categoria::where('estado', 'activo')->orderBy('nombre', 'asc')->get();
        
        return view('mantenedor.platos.index', compact('platos', 'categorias'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $categorias = Categoria::where('estado', 'activo')->get(); 
        return view('mantenedor.platos.create', compact('categorias'));
    }

    /**
     * Guardar nuevo plato
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'disponible' => 'nullable|boolean', 
            'idCategoria' => 'required|exists:categorias,idCategoria',
            'imagen' => 'nullable|image|max:2048'
        ]);

        // Convertir checkbox a boolean
        $validated['disponible'] = $request->has('disponible') ? 1 : 0;

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('platos', 'public');
            $validated['imagen'] = $path;
        }

        Plato::create($validated); 

        return redirect()->route('mantenedor.platos.index')
            ->with('success', 'Plato registrado exitosamente.');
    }
    
    /**
     * Confirmar Eliminación
     */
    public function confirmar($id)
    {
        $plato = Plato::with('categoria')->findOrFail($id);
        return view('mantenedor.platos.confirmar', compact('plato'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $plato = Plato::findOrFail($id);
        $categorias = Categoria::where('estado', 'activo')->orderBy('nombre', 'asc')->get();
        
        // Debug: verificar que el plato tenga su ID
        // dd($plato->idPlatoProducto); // Descomenta esta línea para debug si es necesario
        
        return view('mantenedor.platos.edit', compact('plato', 'categorias'));
    }

    /**
     * Actualizar un plato existente
     */
    public function update(Request $request, $id)
    {
        $plato = Plato::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'idCategoria' => 'required|exists:categorias,idCategoria', 
            'disponible' => 'nullable|boolean',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        
        // Convertir checkbox a boolean
        $validated['disponible'] = $request->has('disponible') ? 1 : 0;

        if ($request->hasFile('imagen')) {
            // Borrar imagen anterior si existe
            if ($plato->imagen && Storage::disk('public')->exists($plato->imagen)) {
                Storage::disk('public')->delete($plato->imagen);
            }

            $ruta = $request->file('imagen')->store('platos', 'public');
            $validated['imagen'] = $ruta;
        }

        $plato->update($validated);

        return redirect()->route('mantenedor.platos.index')->with('success', 'Plato actualizado correctamente');
    }

    /**
     * Eliminar un plato
     */
    public function destroy($id)
    {
        $plato = Plato::findOrFail($id);
        
        // Eliminar imagen si existe
        if ($plato->imagen && Storage::disk('public')->exists($plato->imagen)) {
            Storage::disk('public')->delete($plato->imagen);
        }
        
        $plato->delete();

        return redirect()->route('mantenedor.platos.index')
            ->with('success', 'Plato eliminado correctamente');
    }
}