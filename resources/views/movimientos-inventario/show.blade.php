@extends('layouts.plantilla')
@section('contenido')
<div class="container">
    <h1 class="mb-4">Detalle de Movimiento</h1>
    <ul class="list-group mb-3">
        <li class="list-group-item"><strong>Fecha:</strong> {{ $movimiento->fecha }}</li>
        <li class="list-group-item"><strong>Ingrediente:</strong> {{ $movimiento->ingrediente->nombre ?? 'N/A' }}</li>
        <li class="list-group-item"><strong>Tipo:</strong> {{ ucfirst($movimiento->tipo) }}</li>
        <li class="list-group-item"><strong>Cantidad:</strong> {{ $movimiento->cantidad }}</li>
        <li class="list-group-item"><strong>Motivo:</strong> {{ $movimiento->motivo }}</li>
    </ul>
    <a href="{{ route('movimientos-inventario.index') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection
