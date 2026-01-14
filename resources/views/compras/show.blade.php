{{-- filepath: resources/views/compras/show.blade.php --}}
@extends('layouts.plantilla')
@section('contenido')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Compra #{{ $compra->idCompra }}</h1>
        <div>
            <a href="{{ route('compras.edit', $compra->idCompra) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
            <a href="{{ route('compras.comprobantePDF', $compra->idCompra) }}" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf me-1"></i> Descargar PDF
            </a>
            <a href="{{ route('compras.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información General</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-muted">Proveedor</label>
                            <p class="fw-bold">
                                {{ $compra->proveedor->nombre ?? 'N/A' }} 
                                {{ $compra->proveedor->apellidoPaterno ?? '' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-muted">RUC</label>
                            <p class="fw-bold">{{ $compra->proveedor->rucProveedor ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-muted">Fecha</label>
                            <p class="fw-bold">{{ \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-muted">Estado</label>
                            <p>
                                <span class="badge 
                                    @if($compra->estado=='pendiente') bg-warning text-dark
                                    @elseif($compra->estado=='en_transito') bg-info
                                    @elseif($compra->estado=='recibida') bg-success
                                    @else bg-danger @endif">
                                    {{ ucfirst(str_replace('_', ' ', $compra->estado)) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small text-muted">Descripción</label>
                        <p class="mb-0">{{ $compra->descripcion }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total de Compra</h6>
                    <h2 class="text-success mb-0">S/ {{ number_format($compra->total, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Detalles de Ingredientes ({{ $compra->detalles->count() }})</h5>
        </div>
        <div class="card-body">
            @if($compra->detalles->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="25%">Ingrediente</th>
                                <th width="15%">Cantidad</th>
                                <th width="15%">Recibido</th>
                                <th width="15%">Precio Unit.</th>
                                <th width="20%">Subtotal</th>
                                <th width="10%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compra->detalles as $detalle)
                            <tr>
                                <td>
                                    <strong>{{ $detalle->ingrediente->nombre ?? 'N/A' }}</strong>
                                    @if($detalle->ingrediente)
                                        <br><small class="text-muted">ID: {{ $detalle->idIngrediente }}</small>
                                    @endif
                                </td>
                                <td>{{ $detalle->cantidad }}</td>
                                <td>
                                    <form method="POST" action="{{ route('detalle-compra.update', $detalle->idDetalleCompra) }}" class="d-flex gap-1">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="cantidad_recibida" value="{{ $detalle->cantidad_recibida }}" min="0" max="{{ $detalle->cantidad }}" step="0.01" class="form-control form-control-sm" style="max-width: 80px;">
                                        <button type="submit" class="btn btn-primary btn-sm" title="Actualizar">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
                                <td class="fw-bold">S/ {{ number_format($detalle->subtotal, 2) }}</td>
                                <td>
                                    <form action="{{ route('detalle-compra.destroy', $detalle->idDetalleCompra) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar este ingrediente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="4" class="text-end">TOTAL:</td>
                                <td>S/ {{ number_format($compra->total, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>No hay ingredientes agregados a esta compra.
                    <a href="{{ route('compras.edit', $compra->idCompra) }}" class="alert-link">Agregar ahora</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection