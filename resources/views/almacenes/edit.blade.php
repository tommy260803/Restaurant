{{-- filepath: e:\CICLO 6\ING. REQUERIMIENTOS\RESTAURANT\resources\views\almacenes\edit.blade.php --}}
@extends('layouts.plantilla')
@section('contenido')
<div class="container">
    <h1 class="mb-4">Editar Almacén</h1>
    <form method="POST" action="{{ route('almacenes.update', $almacen->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Almacén</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $almacen->nombre }}" required>
        </div>
        <div class="mb-3">
            <label for="ubicacion" class="form-label">Ubicación</label>
            <input type="text" name="ubicacion" id="ubicacion" class="form-control" value="{{ $almacen->ubicacion }}" required>
        </div>
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Actualizar
        </button>
        <a href="{{ route('almacenes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection