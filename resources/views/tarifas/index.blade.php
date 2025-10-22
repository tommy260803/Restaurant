@extends('layouts.plantilla')

@section('contenido')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="text-dark fw-bold mb-1">
                        <i class="ri-price-tag-3-line text-primary me-2"></i>
                        Gestión de Tarifas
                    </h2>
                    <p class="text-muted mb-0">Administra las tarifas del sistema de registro civil</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('tarifas.create') }}" class="btn btn-primary px-4 py-2 fw-medium">
                        <i class="ri-add-line me-1"></i>
                        Nueva Tarifa
                    </a>
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
        <div id="mensaje" class="alert alert-info alert-dismissible fade show mb-4 border-0 rounded-3 shadow-sm">
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
            <form method="GET" action="{{ route('tarifas.index') }}" class="row g-3 align-items-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="ri-search-line text-muted"></i>
                        </span>
                        <input type="text" name="buscar" class="form-control border-start-0 ps-0" 
                               placeholder="Buscar por tipo de acta..." value="{{ request('buscar') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary flex-fill" type="submit">
                            <i class="ri-search-line me-1"></i>
                            Buscar
                        </button>
                        @if(request('buscar'))
                            <a href="{{ route('tarifas.index') }}" class="btn btn-outline-secondary">
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
                        Lista de Tarifas
                    </h5>
                </div>
                <div class="col-auto">
                    <span class="badge bg-light text-dark px-3 py-2">
                        {{ $tarifas->total() }} {{ $tarifas->total() == 1 ? 'tarifa' : 'tarifas' }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3 text-dark fw-semibold">ID</th>
                        <th class="px-4 py-3 text-dark fw-semibold">Tipo de Acta</th>
                        <th class="px-4 py-3 text-dark fw-semibold">Monto</th>
                        <th class="px-4 py-3 text-dark fw-semibold">Vigente Desde</th>
                        <th class="px-4 py-3 text-dark fw-semibold">Vigente Hasta</th>
                        <th class="px-4 py-3 text-dark fw-semibold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tarifas as $tarifa)
                        <tr class="tarifa-row">
                            <td class="px-4 py-3">
                                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                                    #{{ str_pad($tarifa->id_tarifa, 3, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <i class="ri-file-text-line text-muted me-2"></i>
                                    <span class="fw-medium">{{ $tarifa->tipo_acta }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="fw-bold text-success">
                                    S/. {{ number_format($tarifa->monto, 2) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-muted">
                                    {{ \Carbon\Carbon::parse($tarifa->vigente_desde)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-muted">
                                    {{ \Carbon\Carbon::parse($tarifa->vigente_hasta)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('tarifas.edit', $tarifa->id_tarifa) }}" 
                                       class="btn btn-sm btn-outline-warning" 
                                       title="Editar tarifa">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                    <a href="{{ route('tarifas.confirmar', $tarifa->id_tarifa) }}" 
                                       class="btn btn-sm btn-outline-danger" 
                                       title="Eliminar tarifa">
                                        <i class="ri-delete-bin-line"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="ri-price-tag-3-line text-muted mb-3" style="font-size: 3rem;"></i>
                                    <h5 class="text-muted mb-2">No hay tarifas registradas</h5>
                                    <p class="text-muted mb-3">Comience registrando la primera tarifa del sistema</p>
                                    <a href="{{ route('tarifas.create') }}" class="btn btn-primary">
                                        <i class="ri-add-line me-1"></i>
                                        Registrar Primera Tarifa
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($tarifas->hasPages())
        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Paginación de tarifas">
                {{ $tarifas->withQueryString()->links() }}
            </nav>
        </div>
    @endif
</div>

<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const mensaje = document.getElementById('mensaje');
        if (mensaje) {
            mensaje.classList.add('fade');
            setTimeout(() => mensaje.remove(), 500);
        }
    }, 5000);
</script>

<style>
    /* Custom styles for improved aesthetics */
    .tarifa-row {
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }
    
    .tarifa-row:hover {
        background-color: #f8f9fa;
        border-left-color: #0d6efd;
        transform: translateX(2px);
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
        transition: box-shadow 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }
    
    .empty-state {
        padding: 2rem;
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
    }
    
    .alert-success {
        border-left-color: #198754;
        background-color: #d1e7dd;
    }
    
    .alert-info {
        border-left-color: #0dcaf0;
        background-color: #d1ecf1;
    }
</style>
@endsection