{{-- filepath: e:\CICLO 6\ING. REQUERIMIENTOS\RESTAURANT\resources\views\compras\create.blade.php --}}
@extends('layouts.plantilla')
@section('contenido')
<h1>Nueva Compra</h1>
<form method="POST" action="{{ route('compras.store') }}">
    @csrf
    {{-- Campos de proveedor, fecha, descripción --}}
    {{-- Campos para agregar detalles de compra dinámicamente --}}
    <button type="submit" class="btn btn-success">Guardar</button>
</form>
@endsection