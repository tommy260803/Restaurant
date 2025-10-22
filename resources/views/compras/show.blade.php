{{-- filepath: e:\CICLO 6\ING. REQUERIMIENTOS\RESTAURANT\resources\views\compras\show.blade.php --}}
@extends('layouts.plantilla')
@section('contenido')
<div class="container">
    <h1 class="mb-4">Compra #{{ $compra->idCompra }}</h1>
    <p><strong>Proveedor:</strong> {{ $compra->proveedor->nombre ?? 'N/A' }}</p>
    <p><strong>Fecha:</strong> {{ $compra->fecha }}</p>
    <p><strong>Descripción:</strong> {{ $compra->descripcion }}</p>
    <p><strong>Total:</strong> S/ {{ number_format($compra->total,2) }}</p>
    <p><strong>Estado:</strong> <span class="badge bg-info">{{ ucfirst($compra->estado) }}</span></p>
    <hr>
    <h4>Detalles</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Ingrediente</th>
                <th>Cantidad</th>
                <th>Recibido</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
                <th>Lotes</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($compra->detalles as $detalle)
            <tr>
                <td>{{ $detalle->ingrediente->nombre ?? 'N/A' }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>
                    <form method="POST" action="{{ route('detalle-compra.update', $detalle->idDetalleCompra) }}">
                        @csrf
                        @method('PUT')
                        <input type="number" name="cantidad_recibida" value="{{ $detalle->cantidad_recibida }}" min="0" max="{{ $detalle->cantidad }}" class="form-control form-control-sm d-inline-block w-50">
                        <button type="submit" class="btn btn-primary btn-sm">Registrar</button>
                    </form>
                </td>
                <td>S/ {{ number_format($detalle->precio_unitario,2) }}</td>
                <td>S/ {{ number_format($detalle->subtotal,2) }}</td>
                <td>
                    @foreach($detalle->lotes as $lote)
                        <div>Lote: {{ $lote->lote }} | Vence: {{ $lote->fecha_vencimiento }} | Cant: {{ $lote->cantidad }}</div>
                    @endforeach
                    <form method="POST" action="{{ route('ingrediente_lotes.store', [$detalle->idIngrediente]) }}">
                        @csrf
                        <input type="hidden" name="idDetalleCompra" value="{{ $detalle->idDetalleCompra }}">
                        <input type="text" name="lote" placeholder="Lote" class="form-control form-control-sm mb-1">
                        <input type="date" name="fecha_vencimiento" class="form-control form-control-sm mb-1">
                        <input type="number" name="cantidad" min="0" class="form-control form-control-sm mb-1" placeholder="Cantidad">
                        <button type="submit" class="btn btn-success btn-sm">+ Agregar lote</button>
                    </form>
                </td>
                <td>
                    <form action="{{ route('detalle-compra.destroy', $detalle->idDetalleCompra) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar este detalle?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('compras.index') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection