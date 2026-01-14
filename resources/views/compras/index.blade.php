{{-- resources/views/compras/index.blade.php --}}
@extends('layouts.plantilla')

@section('contenido')
<div class="container-fluid py-4">
    
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-cart-fill text-primary me-2"></i>
                Gestión de Compras
            </h1>
            <p class="text-muted mb-0">Administra las compras y proveedores</p>
        </div>
        <a href="{{ route('compras.create') }}" class="btn btn-success rounded-pill shadow-sm px-4">
            <i class="bi bi-plus-circle me-2"></i>Nueva Compra
        </a>
    </div>

    {{-- Filtros --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-funnel me-2"></i>Filtros de Búsqueda
            </h6>
        </div>
        <div class="card-body">
            <form method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small text-muted">
                            <i class="bi bi-person me-1"></i>Proveedor
                        </label>
                        <select name="proveedor" class="form-select">
                            <option value="">-- Todos los proveedores --</option>
                            @if(isset($proveedores))
                                @foreach($proveedores as $prov)
                                    <option value="{{ $prov->idProveedor }}" {{ request('proveedor') == $prov->idProveedor ? 'selected' : '' }}>
                                        {{ $prov->nombre }} {{ $prov->apellidoPaterno }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="bi bi-calendar-check me-1"></i>Fecha Desde
                        </label>
                        <input type="date" name="desde" class="form-control" value="{{ request('desde') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="bi bi-calendar-x me-1"></i>Fecha Hasta
                        </label>
                        <input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de compras --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient text-white border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>Lista de Compras
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 ps-4">
                                <i class="bi bi-hash me-1"></i>ID
                            </th>
                            <th>
                                <i class="bi bi-person me-1"></i>Proveedor
                            </th>
                            <th>
                                <i class="bi bi-calendar me-1"></i>Fecha
                            </th>
                            <th>
                                <i class="bi bi-card-text me-1"></i>Descripción
                            </th>
                            <th>
                                <i class="bi bi-cash me-1"></i>Total
                            </th>
                            <th>
                                <i class="bi bi-flag me-1"></i>Estado
                            </th>
                            <th class="text-center" style="width: 220px;">
                                <i class="bi bi-gear me-1"></i>Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($compras as $compra)
                        <tr>
                            <td class="fw-semibold ps-4">#{{ $compra->idCompra }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $compra->proveedor->nombre ?? 'N/A' }} {{ $compra->proveedor->apellidoPaterno ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $compra->descripcion }}">
                                    {{ $compra->descripcion }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold text-success">
                                    S/ {{ number_format($compra->total, 2) }}
                                </span>
                            </td>
                            <td>
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
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('compras.show', $compra->idCompra) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('compras.edit', $compra->idCompra) }}" 
                                       class="btn btn-sm btn-outline-warning" 
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('compras.comprobantePDF', $compra->idCompra) }}" 
                                       class="btn btn-sm btn-outline-secondary" 
                                       target="_blank" 
                                       title="Descargar PDF">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </a>
                                    <form action="{{ route('compras.destroy', $compra->idCompra) }}" 
                                          method="POST" 
                                          style="display:inline-block;"
                                          onsubmit="return confirmarEliminar(event);">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.2;"></i>
                                    <h5 class="mt-3 mb-2">No hay compras registradas</h5>
                                    <p class="mb-3">Comienza registrando tu primera compra</p>
                                    <a href="{{ route('compras.create') }}" class="btn btn-success rounded-2">
                                        <i class="bi bi-plus-circle me-2"></i>Nueva Compra
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($compras->hasPages())
        <div class="card-footer bg-light border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Mostrando {{ $compras->firstItem() }} - {{ $compras->lastItem() }} de {{ $compras->total() }} compras
                </div>
                <div>
                    {{ $compras->links() }}
                </div>
            </div>
        </div>
        @endif
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
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    /* Tabla mejorada */
    .table-hover tbody tr {
        transition: all 0.2s ease;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
        transform: scale(1.005);
    }
    
    /* Botones mejorados */
    .btn-group .btn {
        border-radius: 0;
    }
    
    .btn-group .btn:first-child {
        border-top-left-radius: 6px;
        border-bottom-left-radius: 6px;
    }
    
    .btn-group .btn:last-child {
        border-top-right-radius: 6px;
        border-bottom-right-radius: 6px;
    }
    
    .btn-outline-info:hover {
        background-color: #0dcaf0;
        color: white;
    }
    
    .btn-outline-warning:hover {
        background-color: #ffc107;
        color: #000;
    }
    
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }
    
    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
    }
    
    /* Badges mejorados */
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
    }
    
    /* Avatar */
    .avatar-sm {
        font-size: 1rem;
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
    
    /* Responsive */
    @media (max-width: 768px) {
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
        }
        
        .btn-group .btn {
            border-radius: 6px !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function confirmarEliminar(event) {
        event.preventDefault();
        
        Swal.fire({
            title: '¿Eliminar esta compra?',
            text: "Esta acción no se puede deshacer",
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