@extends('layouts.plantilla')

@section('contenido')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="text-dark fw-bold mb-1">
                        <i class="ri-money-dollar-circle-line text-primary me-2"></i>
                        Gestión de Pagos
                    </h2>
                    <p class="text-muted mb-0">Administra los pagos del sistema de registro civil</p>
                </div>
                <div class="d-flex gap-2">
                    <!-- Opciones adicionales si es necesario -->
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts Section -->
    @if(session('success'))
        <div id="mensaje" class="alert alert-success alert-dismissible fade show mb-4 border-0 rounded-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="ri-check-circle-line text-success me-2 fs-5"></i>
                <div>
                    <strong>¡Éxito!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('datos'))
        <div id="mensaje-datos" class="alert alert-info alert-dismissible fade show mb-4 border-0 rounded-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="ri-information-line text-info me-2 fs-5"></i>
                <div>
                    <strong>Información:</strong> {{ session('datos') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('pagos.index') }}" class="row g-3 align-items-center">
                <div class="col-md-10">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="ri-search-line text-muted"></i>
                        </span>
                        <input type="text" name="buscar" class="form-control border-start-0 ps-0" 
                               placeholder="Buscar por DNI o número de transacción..." value="{{ request('buscar') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary flex-fill" type="submit">
                            <i class="ri-search-line me-1"></i>
                            Buscar
                        </button>
                        @if(request('buscar'))
                            <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-close-line"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="card-title mb-0 text-dark">
                        <i class="ri-file-list-3-line me-2"></i>
                        Lista de Pagos
                    </h5>
                </div>
                <div class="col-auto">
                    <span class="badge bg-light text-dark px-3 py-2">
                        {{ $pagos->total() }} {{ $pagos->total() == 1 ? 'pago' : 'pagos' }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3 text-dark fw-semibold">ID</th>
                            <th class="px-4 py-3 text-dark fw-semibold">DNI</th>
                            <th class="px-4 py-3 text-dark fw-semibold">Correo</th>
                            <th class="px-4 py-3 text-dark fw-semibold">Tipo de Acta</th>
                            <th class="px-4 py-3 text-dark fw-semibold">ID Acta</th>
                            <th class="px-4 py-3 text-dark fw-semibold">Monto</th>
                            <th class="px-4 py-3 text-dark fw-semibold">Método</th>
                            <th class="px-4 py-3 text-dark fw-semibold">Estado</th>
                            <th class="px-4 py-3 text-dark fw-semibold">Transacción</th>
                            <th class="px-4 py-3 text-dark fw-semibold">Fecha</th>
                            <th class="px-4 py-3 text-dark fw-semibold text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pagos as $pago)
                            <tr class="pago-row">
                                <td class="px-4 py-3">
                                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                                        #{{ str_pad($pago->id_pago, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2">
                                            <i class="ri-user-line"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">
                                                {{ $pago->DNI ?? 'Sin DNI' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="ri-mail-line text-muted me-2"></i>
                                        <span class="text-truncate" style="max-width: 200px;" title="{{ $pago->Correo }}">
                                            {{ $pago->Correo ?? 'Sin correo' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        @switch($pago->tipo_acta)
                                            @case('acta_nacimiento')
                                                <i class="ri-baby-line text-success me-2"></i>
                                                <span class="fw-medium">Nacimiento</span>
                                                @break
                                            @case('acta_matrimonio')
                                                <i class="ri-heart-line text-danger me-2"></i>
                                                <span class="fw-medium">Matrimonio</span>
                                                @break
                                            @case('acta_defuncion')
                                                <i class="ri-cross-line text-dark me-2"></i>
                                                <span class="fw-medium">Defunción</span>
                                                @break
                                            @default
                                                <i class="ri-file-text-line text-muted me-2"></i>
                                                <span class="fw-medium">{{ ucfirst(str_replace('_', ' ', $pago->tipo_acta)) }}</span>
                                        @endswitch
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($pago->id_acta)
                                        <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill">
                                            {{ $pago->id_acta }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="fw-bold text-success fs-6" style="white-space: nowrap;">
                                        S/. {{ number_format($pago->monto, 2) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @switch($pago->metodo_pago)
                                        @case('efectivo')
                                            <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">
                                                <i class="ri-money-dollar-circle-line me-1"></i>Efectivo
                                            </span>
                                            @break
                                        @case('tarjeta')
                                            <span class="badge bg-primary-subtle text-primary px-2 py-1 rounded-pill">
                                                <i class="ri-bank-card-line me-1"></i>Tarjeta
                                            </span>
                                            @break
                                        @case('transferencia')
                                            <span class="badge bg-info-subtle text-info px-2 py-1 rounded-pill">
                                                <i class="ri-exchange-line me-1"></i>Transferencia
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill">
                                                {{ ucfirst($pago->metodo_pago) }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="px-4 py-3">
                                    @switch($pago->estado)
                                        @case('pendiente')
                                            <span class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill">
                                                <i class="ri-time-line me-1"></i>Pendiente
                                            </span>
                                            @break
                                        @case('completado')
                                            <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">
                                                <i class="ri-check-line me-1"></i>Completado
                                            </span>
                                            @break
                                        @case('fallido')
                                            <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill">
                                                <i class="ri-close-line me-1"></i>Fallido
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill">
                                                {{ ucfirst($pago->estado) }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="px-4 py-3">
                                    @if($pago->num_transaccion)
                                        <code class="text-primary">{{ $pago->num_transaccion }}</code>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="ri-calendar-line me-1"></i>
                                        <span>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('pagos.show', $pago->id_pago) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="Ver detalles del pago"
                                           data-bs-toggle="tooltip">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('pagos.validarPago', $pago->id_pago) }}" 
                                           class="btn btn-sm btn-outline-success" 
                                           title="Validar pago"
                                           data-bs-toggle="tooltip">
                                            <i class="ri-checkbox-circle-line"></i>
                                        </a>
                                        <form action="{{ route('pagos.destroy', $pago->id_pago) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('¿Está seguro de eliminar este pago?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Eliminar pago"
                                                    data-bs-toggle="tooltip">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="ri-money-dollar-circle-line text-muted mb-3" style="font-size: 4rem;"></i>
                                        <h5 class="text-muted mb-2">No hay pagos registrados</h5>
                                        <p class="text-muted mb-3">
                                            @if(request('buscar'))
                                                No se encontraron pagos que coincidan con los criterios de búsqueda.
                                            @else
                                                No hay pagos registrados en el sistema.
                                            @endif
                                        </p>
                                        @if(request('buscar'))
                                            <a href="{{ route('pagos.index') }}" class="btn btn-outline-primary">
                                                <i class="ri-refresh-line me-1"></i>
                                                Ver Todos los Pagos
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($pagos->hasPages())
        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Paginación de pagos">
                {{ $pagos->withQueryString()->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    @endif
</div>

<script>
    setTimeout(() => {
        const mensaje = document.getElementById('mensaje');
        if (mensaje) {
            mensaje.classList.add('fade');
            setTimeout(() => mensaje.remove(), 500);
        }
    }, 5000);

    setTimeout(() => {
        const mensajeDatos = document.getElementById('mensaje-datos');
        if (mensajeDatos) {
            mensajeDatos.classList.add('fade');
            setTimeout(() => mensajeDatos.remove(), 500);
        }
    }, 5000);

    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

<style>
    .pago-row {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }
    
    .pago-row:hover {
        background-color: #f8f9fa;
        border-left-color: #0d6efd;
        transform: translateX(3px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    .btn-group .btn {
        border-radius: 0.375rem;
        margin-right: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .card {
        transition: all 0.3s ease;
        border-radius: 12px;
    }
    
    .card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
        transform: translateY(-2px);
    }
    
    .empty-state {
        padding: 3rem 2rem;
    }
    
    .badge {
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .alert {
        border-left: 4px solid;
        border-radius: 12px;
    }
    
    .alert-success {
        border-left-color: #198754;
        background-color: #d1e7dd;
    }
    
    .alert-info {
        border-left-color: #0dcaf0;
        background-color: #d1ecf1;
    }
    
    .avatar-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }
    
    .table-responsive {
        border-radius: 8px;
    }
    
    .card-header {
        border-radius: 12px 12px 0 0 !important;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    code {
        background-color: #f8f9fa;
        padding: 0.2rem 0.4rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
    }
    
    .fs-6 {
        font-size: 1.1rem !important;
    }
    
    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .px-4 {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
        
        .btn-group .btn {
            margin-right: 0.125rem;
            padding: 0.25rem 0.375rem;
        }
    }
</style>
@endsection