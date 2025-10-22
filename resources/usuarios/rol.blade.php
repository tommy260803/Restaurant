@extends('layouts.plantilla')

@section('contenido')
    <div class="container">
        <h3>Asignar Roles a: {{ $usuario->nombre_usuario }}</h3>
        <form method="POST" action="{{ route('usuarios.rol.update', $usuario->id_usuario) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                @foreach ($roles as $rol)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $rol->name }}"
                            {{ in_array($rol->name, $userRoles) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $rol->name }}</label>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Roles</button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection
