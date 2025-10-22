<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     *  Muestra la lista de permisos
     */
    public function index()
    {
        // Traemos todos los permisos con orden alfabético
        $permisos = Permission::orderBy('name')->get();

        return view('permisos.index', compact('permisos'));
    }

    /**
     *  Formulario de creación
     */
    public function create()
    {
        return view('permisos.create');
    }

    /**
     *  Guarda un nuevo permiso
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:60|unique:permissions,name',
        ]);

        Permission::create([
            'name' => $request->name,
            'guard_name' => 'web', // opcional: por defecto “web”
        ]);

        return redirect()
            ->route('permisos.index')
            ->with('success', 'Permiso creado correctamente.');
    }

    /**
     *  Muestra un permiso concreto
     */
    public function show(Permission $permiso)
    {
        // $permiso llega vía Route Model Binding
        return view('permisos.show', compact('permiso'));
    }

    /**
     *  Formulario de edición
     */
    public function edit(Permission $permiso)
    {
        return view('permisos.edit', compact('permiso'));
    }

    /**
     *  Actualiza un permiso
     */
    public function update(Request $request, Permission $permiso)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:60|unique:permissions,name,' . $permiso->id,
        ]);

        $permiso->update([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('permisos.index')
            ->with('success', 'Permiso actualizado correctamente.');
    }

    /**
     *  Elimina un permiso
     */
    public function destroy(Permission $permiso)
    {
        $permiso->delete();

        return redirect()
            ->route('permisos.index')
            ->with('success', 'Permiso eliminado.');
    }
}
