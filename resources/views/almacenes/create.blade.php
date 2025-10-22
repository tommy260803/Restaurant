{{-- filepath: e:\CICLO 6\ING. REQUERIMIENTOS\RESTAURANT\resources\views\almacenes\create.blade.php --}}
@extends('layouts.plantilla')
@section('contenido')
<div class="container">
    <h1 class="mb-4">Nuevo Almacén</h1>
    <form method="POST" action="{{ route('almacenes.store') }}">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Almacén</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="ubicacion" class="form-label">Ubicación</label>
            <input type="text" name="ubicacion" id="ubicacion" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Guardar
        </button>
        <a href="{{ route('almacenes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection