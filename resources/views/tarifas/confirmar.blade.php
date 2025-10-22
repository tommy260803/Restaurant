@extends('layouts.plantilla')

@section('contenido')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="text-dark fw-bold mb-1">
                        <i class="ri-delete-bin-line text-danger me-2"></i>
                        Confirmar Eliminación
                    </h2>
                    <p class="text-muted mb-0">Revise los datos antes de confirmar la eliminación</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('tarifas.index') }}" class="btn btn-outline-secondary px-4 py-2">
                        <i class="ri-arrow-left-line me-1"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 text-dark">
                        <i class="ri-information-line me-2"></i>
                        Información de la Tarifa
                    </h5>
                </div>
                
                <div class="card-body p-4">
                    <!-- Tarifa Info -->
                    <div class="alert alert-light border-start border-4 border-danger mb-4">
                        <div class="d-flex align-items-center">
                            <i class="ri-price-tag-3-line text-danger me-2 fs-5"></i>
                            <div>
                                <strong>Tarifa a eliminar:</strong>
                                <span class="badge bg-secondary ms-2">ID: {{ $tarifa->id_tarifa }}</span>
                                <span class="ms-2">{{ ucfirst(str_replace('_', ' ', $tarifa->tipo_acta)) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Details Card -->
                    <div class="card bg-light border-0 mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="ri-money-dollar-circle-line text-muted me-2"></i>
                                        <div>
                                            <small class="text-muted">Monto</small>
                                            <div class="fw-medium">S/. {{ number_format($tarifa->monto, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="ri-calendar-check-line text-muted me-2"></i>
                                        <div>
                                            <small class="text-muted">Vigente Desde</small>
                                            <div class="fw-medium">{{ \Carbon\Carbon::parse($tarifa->vigente_desde)->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="ri-calendar-close-line text-muted me-2"></i>
                                        <div>
                                            <small class="text-muted">Vigente Hasta</small>
                                            <div class="fw-medium">{{ $tarifa->vigente_hasta ? \Carbon\Carbon::parse($tarifa->vigente_hasta)->format('d/m/Y') : 'Vigente' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Warning -->
                    <div class="alert alert-danger border-0 rounded-3 mb-4">
                        <div class="d-flex align-items-start">
                            <i class="ri-alarm-warning-line text-danger me-2 fs-5 mt-1"></i>
                            <div>
                                <strong>¿Está seguro que desea eliminar esta tarifa?</strong>
                                <p class="mb-0 mt-2">Esta acción eliminará permanentemente la tarifa del sistema y no se puede deshacer.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('tarifas.destroy', $tarifa->id_tarifa) }}">
                        @csrf
                        @method('DELETE')

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-end pt-3 border-top">
                            <a href="{{ route('tarifas.index') }}" class="btn btn-outline-secondary px-4 py-2">
                                <i class="ri-close-line me-1"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-danger px-4 py-2 fw-medium">
                                <i class="ri-delete-bin-line me-1"></i>
                                Confirmar Eliminación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom styles for form enhancement */
    .card {
        transition: box-shadow 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }
    
    .alert {
        border-left: 4px solid;
    }
    
    .alert-danger {
        border-left-color: #dc3545;
        background-color: #f8d7da;
    }
    
    .alert-light {
        border-left-color: #dc3545;
        background-color: #f8f9fa;
    }
    
    .btn {
        transition: all 0.2s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
    }
    
    .badge {
        font-size: 0.75rem;
    }
    
    .fw-medium {
        font-weight: 500;
    }
</style>
@endsection