@extends('layouts.plantilla')

@section('contenido')
    <div class="container">
        <h2>Crear Permiso</h2>
        <form action="{{ route('permisos.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Nombre del permiso</label>
                <input type="text" name="name" class="form-control" required>
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button class="btn btn-primary">Guardar</button>
        </form>
    </div>
@endsection
