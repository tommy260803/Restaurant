@extends('layouts.plantilla')
@section('contenido')
    <div class="container">
        <h2>Lista de Roles</h2>
        <a href="{{ route('roles.create') }}" class="btn btn-success mb-3">Crear Nuevo Rol</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Rol</th>
                    <th>Permisos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $rol)
                    <tr>
                        <td>{{ $rol->name }}</td>
                        <td>
                            @foreach ($rol->permissions as $permiso)
                                <span class="badge bg-info">{{ $permiso->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('roles.edit', $rol->id) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('roles.destroy', $rol->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('¿Eliminar este rol?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
