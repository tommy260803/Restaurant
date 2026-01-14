{{-- resources/views/compras/show.blade.php --}}
@extends('layouts.plantilla')

@section('contenido')
<div class="container-fluid py-4">
    
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-receipt text-primary me-2"></i>
                Compra #{{ $compra->idCompra }}
            </h1>
            <p class="text-muted mb-0">Detalles de la orden de compra</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('compras.edit', $compra->idCompra) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-1"></i>Editar
            </a>
            <a href="{{ route('compras.comprobantePDF', $compra->idCompra) }}" class="btn btn-danger" target="_blank">
                <i class="bi bi-file-earmark-pdf me-1"></i>PDF
            </a>
            <a href="{{ route('compras.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Volver
            </a>
        </div>
    </div>

    <div class="row mb-4">
        {{-- Información General --}}
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Información General
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="small text-muted mb-1">
                                    <i class="bi bi-person me-1"></i>Proveedor
                                </label>
                                <p class="fw-semibold mb-0">
                                    {{ $compra->proveedor->nombre ?? 'N/A' }} 
                                    {{ $compra->proveedor->apellidoPaterno ?? '' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="small text-muted mb-1">
                                    <i class="bi bi-card-text me-1"></i>RUC
                                </label>
                                <p class="fw-semibold mb-0">{{ $compra->proveedor->rucProveedor ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="small text-muted mb-1">
                                    <i class="bi bi-calendar3 me-1"></i>Fecha
                                </label>
                                <p class="fw-semibold mb-0">{{ \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="small text-muted mb-1">
                                    <i class="bi bi-flag me-1"></i>Estado
                                </label>
                                <p class="mb-0">
                                    @php
                                        $badgeClass = match($compra->estado) {
                                            'pendiente' => 'bg-warning text-dark',
                                            'en_transito' => 'bg-info',
                                            'recibida' => 'bg-success',
                                            default => 'bg-danger'
                                        };
                                        $icon = match($compra->estado) {
                                            'pendiente' => 'hourglass-split',
                                            'en_transito' => 'truck',
                                            'recibida' => 'check-circle',
                                            default => 'x-circle'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-3 py-2">
                                        <i class="bi bi-{{ $icon }} me-1"></i>
                                        {{ ucfirst(str_replace('_', ' ', $compra->estado)) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-item">
                                <label class="small text-muted mb-1">
                                    <i class="bi bi-journal-text me-1"></i>Descripción
                                </label>
                                <p class="mb-0 text-muted">{{ $compra->descripcion }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total de Compra --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-cash-stack text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h6 class="text-muted mb-2">Total de Compra</h6>
                        <h2 class="text-success fw-bold mb-0">S/ {{ number_format($compra->total, 2) }}</h2>
                        <small class="text-muted">Incluye {{ $compra->detalles->count() }} item(s)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detalles de Ingredientes --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0">
                <i class="bi bi-basket me-2"></i>Detalles de Ingredientes ({{ $compra->detalles->count() }})
            </h5>
        </div>
        <div class="card-body p-0">
            @if($compra->detalles->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 ps-4">
                                    <i class="bi bi-box me-1"></i>Ingrediente
                                </th>
                                <th>
                                    <i class="bi bi-123 me-1"></i>Cantidad
                                </th>
                                <th>
                                    <i class="bi bi-check2-square me-1"></i>Recibido
                                </th>
                                <th>
                                    <i class="bi bi-tag me-1"></i>Precio Unit.
                                </th>
                                <th>
                                    <i class="bi bi-calculator me-1"></i>Subtotal
                                </th>
                                <th class="text-center">
                                    <i class="bi bi-gear me-1"></i>Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compra->detalles as $detalle)
                            <tr>
                                <td class="ps-4">
                                    <div>
                                        <strong>{{ $detalle->ingrediente->nombre ?? 'N/A' }}</strong>
                                        @if($detalle->ingrediente)
                                            <br><small class="text-muted">
                                                <i class="bi bi-hash"></i>ID: {{ $detalle->idIngrediente }}
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $detalle->cantidad }}</span>
                                </td>
                                <td>
                                    <form method="POST" 
                                          action="{{ route('detalle-compra.update', $detalle->idDetalleCompra) }}" 
                                          class="d-flex gap-2 align-items-center">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" 
                                               name="cantidad_recibida" 
                                               value="{{ $detalle->cantidad_recibida }}" 
                                               min="0" 
                                               max="{{ $detalle->cantidad }}" 
                                               step="0.01" 
                                               class="form-control form-control-sm" 
                                               style="max-width: 90px;">
                                        <button type="submit" 
                                                class="btn btn-sm btn-primary" 
                                                title="Actualizar cantidad recibida">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <span class="text-muted">S/ {{ number_format($detalle->precio_unitario, 2) }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">S/ {{ number_format($detalle->subtotal, 2) }}</span>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('detalle-compra.destroy', $detalle->idDetalleCompra) }}" 
                                          method="POST" 
                                          style="display:inline-block;"
                                          onsubmit="return confirmarEliminar(event);">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Eliminar ingrediente">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr class="fw-bold">
                                <td colspan="4" class="text-end py-3 pe-4">
                                    <i class="bi bi-calculator me-2"></i>TOTAL:
                                </td>
                                <td class="text-success py-3">
                                    S/ {{ number_format($compra->total, 2) }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="p-5 text-center">
                    <i class="bi bi-inbox text-muted" style="font-size: 4rem; opacity: 0.2;"></i>
                    <h5 class="mt-3 mb-2 text-muted">No hay ingredientes agregados</h5>
                    <p class="text-muted mb-3">Agrega ingredientes a esta compra para comenzar</p>
                    <a href="{{ route('compras.edit', $compra->idCompra) }}" class="btn btn-primary rounded-pill">
                        <i class="bi bi-plus-circle me-2"></i>Agregar Ingredientes
                    </a>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    /* Asegurar que Bootstrap Icons se cargue */
    @import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css');
    
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
    }
    
    /* Cards mejorados */
    .card {
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    /* Info items */
    .info-item {
        padding-bottom: 0.5rem;
    }
    
    .info-item label {
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
    }
    
    /* Tabla mejorada */
    .table-hover tbody tr {
        transition: all 0.2s ease;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
        transform: scale(1.002);
    }
    
    /* Badges */
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
    }
    
    /* Botones */
    .btn {
        transition: all 0.2s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .btn-sm:hover {
        transform: translateY(-1px);
    }
    
    /* Input de cantidad recibida */
    .form-control-sm:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    /* Animaciones */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .card {
        animation: fadeIn 0.3s ease;
    }
    
    .row > div {
        animation: fadeIn 0.3s ease;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .d-flex.gap-2 {
            flex-direction: column;
            gap: 0.5rem !important;
        }
        
        .btn {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function confirmarEliminar(event) {
        event.preventDefault();
        
        Swal.fire({
            title: '¿Eliminar este ingrediente?',
            text: "Se eliminará de esta compra",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            customClass: {
                popup: 'rounded-4 shadow',
                confirmButton: 'btn btn-danger rounded-pill px-4',
                cancelButton: 'btn btn-secondary rounded-pill px-4'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.submit();
            }
        });
        
        return false;
    }
</script>
@endpush