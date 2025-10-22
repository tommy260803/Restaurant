{{-- filepath: e:\CICLO 6\ING. REQUERIMIENTOS\RESTAURANT\resources\views\compras\index.blade.php --}}
@extends('layouts.plantilla')
@section('contenido')
<div class="container">
    <h1 class="mb-4">Compras</h1>
    <form method="GET" class="mb-3">
        <div class="row g-2">
            <div class="col-md-3">
                <input type="number" name="proveedor" class="form-control" placeholder="ID Proveedor" value="{{ request('proveedor') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="desde" class="form-control" value="{{ request('desde') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>
    <a href="{{ route('compras.create') }}" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Nueva Compra
    </a>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Proveedor</th>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($compras as $compra)
                <tr>
                    <td>{{ $compra->idCompra }}</td>
                    <td>{{ $compra->proveedor->nombre ?? 'N/A' }}</td>
                    <td>{{ $compra->fecha }}</td>
                    <td>{{ $compra->descripcion }}</td>
                    <td>S/ {{ number_format($compra->total,2) }}</td>
                    <td>
                        <span class="badge 
                            @if($compra->estado=='pendiente') bg-warning text-dark
                            @elseif($compra->estado=='en_transito') bg-info
                            @elseif($compra->estado=='recibida') bg-success
                            @else bg-danger @endif">
                            {{ ucfirst($compra->estado) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('compras.show', $compra->idCompra) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        <a href="{{ route('compras.edit', $compra->idCompra) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('compras.comprobantePDF', $compra->idCompra) }}" class="btn btn-secondary btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                        <form action="{{ route('compras.destroy', $compra->idCompra) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Seguro que deseas eliminar esta compra?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $compras->links() }}
</div>
@endsection