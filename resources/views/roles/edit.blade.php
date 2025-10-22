@extends('layouts.plantilla')

@section('contenido')
    <div class="container">
        <h3>Editar Rol: {{ $role->name }}</h3>
        bash
        Copiar
        Editar
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nombre del Rol</label>
                <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
            </div>

            <div class="mb-3">
                <label>Permisos</label><br>
                @foreach ($permissions as $permiso)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permiso->name }}"
                            {{ in_array($permiso->name, $rolePermissions) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $permiso->name }}</label>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection
