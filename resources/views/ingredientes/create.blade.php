{{-- filepath: e:\CICLO 6\ING. REQUERIMIENTOS\RESTAURANT\resources\views\ingredientes\create.blade.php --}}
@extends('layouts.plantilla')
@section('contenido')
<div class="container">
    <h1 class="mb-4">Nuevo Ingrediente</h1>
    <form method="POST" action="{{ route('ingredientes.store') }}">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="unidad" class="form-label">Unidad</label>
            <input type="text" name="unidad" id="unidad" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" name="stock" id="stock" class="form-control" min="0" required>
        </div>
        <div class="mb-3">
            <label for="stock_minimo" class="form-label">Stock Mínimo</label>
            <input type="number" name="stock_minimo" id="stock_minimo" class="form-control" min="0" required>
        </div>
        <div class="mb-3">
            <label for="costo_promedio" class="form-label">Costo Promedio</label>
            <input type="number" step="0.01" name="costo_promedio" id="costo_promedio" class="form-control" min="0" required>
        </div>
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Guardar
        </button>
        <a href="{{ route('ingredientes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection