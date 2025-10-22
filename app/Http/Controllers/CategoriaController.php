<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Mostrar listado de categorías
     */
    public function index(Request $request)
    {
        $query = Categoria::query();

        // Buscar por nombre
        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $categorias = $query->orderBy('idCategoria', 'desc')->paginate(10);

        // Contador solo de activas
        $totalActivas = Categoria::where('estado', 'activo')->count();

        return view('mantenedor.categoria.index', compact('categorias', 'totalActivas'));
    }


    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('mantenedor.categoria.create');
    }

    /**
     * Guardar nueva categoría
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'estado' => 'required|in:activo,inactivo',
        ]);

        Categoria::create($request->all());

        return redirect()->route('mantenedor.categorias.index')
                         ->with('success', 'Categoría registrada correctamente.');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Categoria $categoria)
    {
        return view('mantenedor.categoria.edit', compact('categoria'));
    }

    /**
     * Actualizar categoría existente
     */
    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $categoria->update($request->all());

        return redirect()->route('mantenedor.categorias.index')
                         ->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Confirmar eliminación (vista intermedia)
     */
    public function confirmar(Categoria $categoria)
    {
        return view('mantenedor.categoria.confirmar', compact('categoria'));
    }

    /**
     * Eliminar / Desactivar categoría
     */
    public function destroy(Categoria $categoria)
    {
        // En lugar de eliminar, marcamos como inactivo
        $categoria->update(['estado' => 'inactivo']);

        return redirect()->route('mantenedor.categorias.index')
                         ->with('success', 'Categoría eliminada correctamente.');
    }
}
