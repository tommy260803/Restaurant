{{-- filepath: e:\CICLO 6\ING. REQUERIMIENTOS\RESTAURANT\resources\views\compras\create.blade.php --}}
@extends('layouts.plantilla')
@section('contenido')
<div class="container">
    <h1 class="mb-4">Nueva Compra</h1>
    <form method="POST" action="{{ route('compras.store') }}">
        @csrf
        <div class="mb-3">
            <label for="idProveedor" class="form-label">Proveedor</label>
            <select name="idProveedor" id="idProveedor" class="form-select" required>
                {{-- Itera sobre $proveedores --}}
                {{-- <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option> --}}
            </select>
        </div>
        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" name="fecha" id="fecha" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
        </div>
        <hr>
        <h4>Detalles de Compra</h4>
        <div id="detallesCompra">
            {{-- Aquí se agregan dinámicamente los detalles con JS --}}
        </div>
        <button type="button" class="btn btn-outline-primary mb-3" onclick="agregarDetalle()">+ Agregar ingrediente</button>
        <button type="submit" class="btn btn-success">Guardar Compra</button>
        <a href="{{ route('compras.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<script>
function agregarDetalle() {
    // Implementa con JS: agrega campos para ingrediente, cantidad, precio_unitario, etc.
}
</script>
@endsection