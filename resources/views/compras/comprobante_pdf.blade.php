{{-- filepath: e:\CICLO 6\ING. REQUERIMIENTOS\RESTAURANT\resources\views\compras\comprobante_pdf.blade.php --}}
@extends('layouts.plantilla')
@section('contenido')
<div class="container">
    <h2>Comprobante de Compra #{{ $compra->idCompra }}</h2>
    <p><strong>Proveedor:</strong> {{ $compra->proveedor->nombre ?? 'N/A' }}</p>
    <p><strong>Fecha:</strong> {{ $compra->fecha }}</p>
    <p><strong>Descripción:</strong> {{ $compra->descripcion }}</p>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Ingrediente</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($compra->detalles as $detalle)
            <tr>
                <td>{{ $detalle->ingrediente->nombre ?? 'N/A' }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>S/ {{ number_format($detalle->precio_unitario,2) }}</td>
                <td>S/ {{ number_format($detalle->subtotal,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p><strong>Total:</strong> S/ {{ number_format($compra->total,2) }}</p>
</div>
@endsection