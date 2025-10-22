{{-- filepath: e:\CICLO 6\ING. REQUERIMIENTOS\RESTAURANT\resources\views\ingredientes\show.blade.php --}}
@extends('layouts.plantilla')
@section('contenido')
<div class="container">
    <h1 class="mb-4">{{ $ingrediente->nombre }}</h1>
    <p><strong>Unidad:</strong> {{ $ingrediente->unidad }}</p>
    <p><strong>Stock:</strong> {{ $ingrediente->stock }}</p>
    <p><strong>Stock mínimo:</strong> {{ $ingrediente->stock_minimo }}</p>
    <p><strong>Costo promedio:</strong> S/ {{ number_format($ingrediente->costo_promedio,2) }}</p>
    <h4>Lotes</h4>
    @if($ingrediente->lotes->count())
    <ul class="list-group mb-3">
        @foreach($ingrediente->lotes as $lote)
        <li class="list-group-item">
            <strong>Lote:</strong> {{ $lote->lote }} |
            <strong>Vence:</strong> {{ $lote->fecha_vencimiento }} |
            <strong>Cantidad:</strong> {{ $lote->cantidad }}
        </li>
        @endforeach
    </ul>
    @else
        <div class="alert alert-warning">No hay lotes registrados.</div>
    @endif
    <h4>Movimientos</h4>
    @if($ingrediente->movimientos->count())
    <ul class="list-group mb-3">
        @foreach($ingrediente->movimientos as $mov)
        <li class="list-group-item">
            <strong>{{ ucfirst($mov->tipo) }}:</strong> {{ $mov->cantidad }} 
            <span class="text-muted">({{ $mov->motivo }})</span> - {{ $mov->fecha }}
        </li>
        @endforeach
    </ul>
    @else
        <div class="alert alert-info">No hay movimientos registrados.</div>
    @endif
    <a href="{{ route('ingredientes.index') }}" class="btn btn-secondary">Volver</a>
    <a href="{{ route('ingredientes.edit', $ingrediente->id) }}" class="btn btn-warning">Editar</a>
    {{-- Formulario de ajuste de stock --}}
    <form method="POST" action="{{ route('ingredientes.ajustarStock', $ingrediente->id) }}" class="mt-3">
        @csrf
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="tipo" class="form-label">Tipo de movimiento</label>
                <select name="tipo" id="tipo" class="form-select" required>
                    <option value="entrada">Entrada</option>
                    <option value="salida">Salida</option>
                    <option value="ajuste">Ajuste</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" name="cantidad" id="cantidad" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="motivo" class="form-label">Motivo</label>
                <input type="text" name="motivo" id="motivo" class="form-control">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Ajustar Stock</button>
            </div>
        </div>
    </form>
</div>
@endsection