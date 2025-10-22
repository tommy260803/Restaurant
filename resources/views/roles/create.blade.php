@extends('layouts.plantilla')

@section('contenido')
    <div class="container">
        <h2>Crear Rol</h2>
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name">Nombre del Rol:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <h5>Permisos:</h5>
            <div class="mb-3">
                @foreach ($permisos as $permiso)
                    <div class="form-check">
                        <input type="checkbox" name="permissions[]" value="{{ $permiso->name }}" class="form-check-input">
                        <label class="form-check-label">{{ $permiso->name }}</label>
                    </div>
                @endforeach
            </div>
            <button type="submit" class="btn btn-primary">Crear Rol</button>
        </form>
    </div>
@endsection
