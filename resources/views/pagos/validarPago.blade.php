@extends('layouts.plantilla')

@section('contenido')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="text-dark fw-bold mb-1">
                        <i class="ri-checkbox-circle-line text-warning me-2"></i>
                        Validar Pago
                    </h2>
                    <p class="text-muted mb-0">Revisa y valida el número de transacción del pago</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary px-4 py-2 fw-medium">
                        <i class="ri-arrow-left-line me-1"></i>
                        Volver
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

    @if(session('error'))
        <div id="mensaje-error" class="alert alert-danger alert-dismissible fade show mb-4 border-0 rounded-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="ri-error-warning-line text-danger me-2 fs-5"></i>
                <div>
                    <strong>Error:</strong> {{ session('error') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Payment Details Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 text-dark">
                        <i class="ri-file-list-3-line me-2"></i>
                        Detalles del Pago
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Payment ID -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label text-muted mb-1">ID de Pago</label>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fs-6">
                                        #{{ str_pad($pago->id_pago, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- DNI -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label text-muted mb-1">DNI del Usuario</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-user-line text-primary me-2"></i>
                                    <span class="fw-semibold">{{ $pago->DNI }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label text-muted mb-1">Correo Electrónico</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-mail-line text-primary me-2"></i>
                                    <span class="fw-semibold">{{ $pago->Correo }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Document Type -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label text-muted mb-1">Tipo de Acta</label>
                                <div class="d-flex align-items-center">
                                    @switch($pago->tipo_acta)
                                        @case('acta_nacimiento')
                                            <i class="ri-baby-line text-success me-2"></i>
                                            <span class="fw-semibold">Acta de Nacimiento</span>
                                            @break
                                        @case('acta_matrimonio')
                                            <i class="ri-heart-line text-danger me-2"></i>
                                            <span class="fw-semibold">Acta de Matrimonio</span>
                                            @break
                                        @case('acta_defuncion')
                                            <i class="ri-cross-line text-dark me-2"></i>
                                            <span class="fw-semibold">Acta de Defunción</span>
                                            @break
                                        @default
                                            <i class="ri-file-text-line text-muted me-2"></i>
                                            <span class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $pago->tipo_acta)) }}</span>
                                    @endswitch
                                </div>
                            </div>
                        </div>

                        <!-- Document ID -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label text-muted mb-1">ID del Acta</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-file-text-line text-primary me-2"></i>
                                    <span class="fw-semibold">
                                        {{ $pago->id_acta ?? 'No asignado' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label text-muted mb-1">Monto</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-money-dollar-circle-line text-success me-2"></i>
                                    <span class="fw-bold text-success fs-4">S/. {{ number_format($pago->monto, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label text-muted mb-1">Método de Pago</label>
                                <div class="d-flex align-items-center">
                                    @switch($pago->metodo_pago)
                                        @case('efectivo')
                                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                                <i class="ri-money-dollar-circle-line me-1"></i>Efectivo
                                            </span>
                                            @break
                                        @case('tarjeta')
                                            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                                                <i class="ri-bank-card-line me-1"></i>Tarjeta
                                            </span>
                                            @break
                                        @case('transferencia')
                                            <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">
                                                <i class="ri-exchange-line me-1"></i>Transferencia
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">
                                                {{ ucfirst($pago->metodo_pago) }}
                                            </span>
                                    @endswitch
                                </div>
                            </div>
                        </div>

                        <!-- Transaction Number -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label text-muted mb-1">Número de Transacción</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-barcode-line text-primary me-2"></i>
                                    <code class="text-primary fs-5 fw-bold">
                                        {{ $pago->num_transaccion ?? 'No proporcionado' }}
                                    </code>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Date -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label text-muted mb-1">Fecha de Pago</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-calendar-line text-primary me-2"></i>
                                    <span class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Current Status -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label text-muted mb-1">Estado Actual</label>
                                <div class="d-flex align-items-center">
                                    @switch($pago->estado)
                                        @case('completado')
                                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                                <i class="ri-check-circle-line me-1"></i>Completado
                                            </span>
                                            @break
                                        @case('fallido')
                                            <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                                <i class="ri-close-circle-line me-1"></i>Fallido
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">
                                                <i class="ri-time-line me-1"></i>Pendiente
                                            </span>
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Validation Section -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning-subtle border-bottom">
                    <h5 class="card-title mb-0 text-warning">
                        <i class="ri-shield-check-line me-2"></i>
                        Validación de Administrador
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0 mb-4">
                        <div class="d-flex align-items-start">
                            <i class="ri-information-line text-info me-2 mt-1"></i>
                            <div>
                                <strong>Administrador:</strong><br>
                                Revisa el número de transacción y decide si el pago es válido o inválido.
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Verification -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-2">
                            <i class="ri-search-line me-1"></i>
                            Número de Transacción
                        </label>
                        <div class="bg-light p-3 rounded">
                            <code class="text-primary fs-6 fw-bold">
                                {{ $pago->num_transaccion ?? 'No proporcionado' }}
                            </code>
                        </div>
                    </div>

                    <!-- Validation Actions -->
                    @if($pago->estado === 'pendiente')
                        <div class="d-grid gap-3">
                            <!-- Pago Válido -->
                            <form action="{{ route('pagos.update', $pago->id_pago) }}" method="POST" class="validation-form">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="estado" value="completado">
                            <button type="submit" class="btn btn-success btn-lg fw-semibold w-100">
                                <i class="ri-check-double-line me-2"></i>
                                Pago Válido
                            </button>
                        </form>

                        <form action="{{ route('pagos.update', $pago->id_pago) }}" method="POST" class="validation-form">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="estado" value="fallido">
                            <button type="submit" class="btn btn-danger btn-lg fw-semibold w-100">
                                <i class="ri-close-circle-line me-2"></i>
                                Pago Inválido
                            </button>
                        </form>
                        </div>
                    @else
                        <div class="alert alert-secondary border-0 text-center">
                            <i class="ri-lock-line fs-4 mb-2 d-block"></i>
                            <strong>Pago ya procesado</strong><br>
                            <small class="text-muted">Este pago ya ha sido validado y no puede modificarse.</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Instructions -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-info-subtle border-bottom">
                    <h6 class="card-title mb-0 text-info">
                        <i class="ri-lightbulb-line me-2"></i>
                        Instrucciones
                    </h6>
                </div>
                <div class="card-body">
                    <div class="small text-muted">
                        <div class="mb-2">
                            <i class="ri-arrow-right-s-line me-1"></i>
                            <strong>Pago Válido:</strong> Cambia el estado a "Completado"
                        </div>
                        <div class="mb-2">
                            <i class="ri-arrow-right-s-line me-1"></i>
                            <strong>Pago Inválido:</strong> Cambia el estado a "Fallido"
                        </div>
                        <div class="mb-2">
                            <i class="ri-arrow-right-s-line me-1"></i>
                            Verifica el número de transacción antes de decidir
                        </div>
                        <div>
                            <i class="ri-arrow-right-s-line me-1"></i>
                            Las acciones de validación no se pueden deshacer
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-hide alerts
    setTimeout(() => {
        const mensaje = document.getElementById('mensaje');
        if (mensaje) {
            mensaje.classList.add('fade');
            setTimeout(() => mensaje.remove(), 500);
        }
    }, 5000);

    setTimeout(() => {
        const mensajeError = document.getElementById('mensaje-error');
        if (mensajeError) {
            mensajeError.classList.add('fade');
            setTimeout(() => mensajeError.remove(), 500);
        }
    }, 7000);
</script>

<style>
    .card {
        transition: all 0.3s ease;
        border-radius: 12px;
    }
    
    .card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
        transform: translateY(-2px);
    }
    
    .alert {
        border-left: 4px solid;
        border-radius: 12px;
    }
    
    .alert-success {
        border-left-color: #198754;
        background-color: #d1e7dd;
    }
    
    .alert-danger {
        border-left-color: #dc3545;
        background-color: #f8d7da;
    }
    
    .alert-warning {
        border-left-color: #ffc107;
        background-color: #fff3cd;
    }
    
    .alert-info {
        border-left-color: #0dcaf0;
        background-color: #d1ecf1;
    }
    
    .form-group label {
        font-weight: 600;
        color: #495057;
    }
    
    .badge {
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .bg-warning-subtle {
        background-color: #fff3cd !important;
    }
    
    .bg-info-subtle {
        background-color: #d1ecf1 !important;
    }
    
    .bg-success-subtle {
        background-color: #d1e7dd !important;
    }
    
    .bg-danger-subtle {
        background-color: #f8d7da !important;
    }
    
    .text-warning {
        color: #856404 !important;
    }
    
    .text-info {
        color: #0c5460 !important;
    }
    
    .text-success {
        color: #0f5132 !important;
    }
    
    .text-danger {
        color: #721c24 !important;
    }
    
    code {
        background-color: #f8f9fa;
        padding: 0.3rem 0.6rem;
        border-radius: 0.375rem;
        font-size: 0.95rem;
        border: 1px solid #dee2e6;
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
    }
    
    .fs-4 {
        font-size: 1.5rem !important;
    }
    
    .fs-5 {
        font-size: 1.25rem !important;
    }
    
    .fs-6 {
        font-size: 1.1rem !important;
    }
    
    .validation-form {
        margin: 0;
    }
    
    .btn-success:hover {
        background-color: #157347;
        border-color: #146c43;
    }
    
    .btn-danger:hover {
        background-color: #bb2d3b;
        border-color: #b02a37;
    }
    
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .btn-lg {
            padding: 0.5rem 1rem;
            font-size: 1rem;
        }
    }
</style>
@endsection