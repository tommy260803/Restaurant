@extends('layouts.plantilla')

@section('contenido')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="text-dark fw-bold mb-1">
                        <i class="ri-add-circle-line text-primary me-2"></i>
                        Registrar Nueva Tarifa
                    </h2>
                    <p class="text-muted mb-0">Complete los datos para crear una nueva tarifa en el sistema</p>
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

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 rounded-3 shadow-sm">
            <div class="d-flex align-items-start">
                <i class="ri-error-warning-line text-danger me-2 fs-5 mt-1"></i>
                <div class="flex-grow-1">
                    <strong>¡Atención!</strong> Por favor corrige los siguientes errores:
                    <ul class="mb-0 mt-2 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 text-dark">
                        <i class="ri-file-text-line me-2"></i>
                        Información de la Tarifa
                    </h5>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('tarifas.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="tipo_acta" class="form-label text-dark fw-medium">
                                    <i class="ri-file-list-3-line me-1"></i>
                                    Tipo de Acta <span class="text-danger">*</span>
                                </label>
                                <select name="tipo_acta" id="tipo_acta" class="form-select form-select-lg @error('tipo_acta') is-invalid @enderror" required>
                                    <option value="">Seleccione tipo de acta</option>
                                    <option value="acta_nacimiento" {{ old('tipo_acta') == 'acta_nacimiento' ? 'selected' : '' }}>
                                        Acta de Nacimiento
                                    </option>
                                    <option value="acta_matrimonio" {{ old('tipo_acta') == 'acta_matrimonio' ? 'selected' : '' }}>
                                        Acta de Matrimonio
                                    </option>
                                    <option value="acta_defuncion" {{ old('tipo_acta') == 'acta_defuncion' ? 'selected' : '' }}>
                                        Acta de Defunción
                                    </option>
                                </select>
                                @error('tipo_acta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="monto" class="form-label text-dark fw-medium">
                                    <i class="ri-money-dollar-circle-line me-1"></i>
                                    Monto (S/.) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">S/.</span>
                                    <input type="number" name="monto" id="monto" 
                                           class="form-control form-control-lg @error('monto') is-invalid @enderror" 
                                           min="0" step="0.01" placeholder="25.00" 
                                           value="{{ old('monto') }}" required>
                                    @error('monto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="vigente_desde" class="form-label text-dark fw-medium">
                                    <i class="ri-calendar-check-line me-1"></i>
                                    Vigente Desde <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="vigente_desde" id="vigente_desde" 
                                       class="form-control form-control-lg @error('vigente_desde') is-invalid @enderror" 
                                       value="{{ old('vigente_desde') }}" required>
                                @error('vigente_desde')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="vigente_hasta" class="form-label text-dark fw-medium">
                                    <i class="ri-calendar-close-line me-1"></i>
                                    Vigente Hasta
                                </label>
                                <input type="date" name="vigente_hasta" id="vigente_hasta" 
                                       class="form-control form-control-lg @error('vigente_hasta') is-invalid @enderror" 
                                       value="{{ old('vigente_hasta') }}">
                                <small class="text-muted mt-1">
                                    <i class="ri-information-line me-1"></i>
                                    Opcional. Dejar en blanco si la tarifa aún está vigente.
                                </small>
                                @error('vigente_hasta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Info Card -->
                        <div class="alert alert-info border-0 rounded-3 mb-4">
                            <div class="d-flex align-items-start">
                                <i class="ri-lightbulb-line text-info me-2 fs-5 mt-1"></i>
                                <div>
                                    <strong>Consejos:</strong>
                                    <ul class="mb-0 mt-2 ps-3">
                                        <li>Asegúrese de que el monto sea preciso y esté en soles peruanos</li>
                                        <li>La fecha de vigencia debe coincidir con las normativas actuales</li>
                                        <li>Si no especifica fecha de fin, la tarifa se considerará vigente indefinidamente</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-end pt-3 border-top">
                            <a href="{{ route('tarifas.index') }}" class="btn btn-outline-secondary px-4 py-2">
                                <i class="ri-close-line me-1"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-medium">
                                <i class="ri-save-line me-1"></i>
                                Registrar Tarifa
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
    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    .form-control-lg,
    .form-select-lg {
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }
    
    .input-group-text {
        font-weight: 500;
    }
    
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
    
    .alert-info {
        border-left-color: #0dcaf0;
        background-color: #d1ecf1;
    }
    
    .form-label {
        margin-bottom: 0.5rem;
    }
    
    .btn {
        transition: all 0.2s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
    }
    
    .invalid-feedback {
        display: block;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .is-invalid {
        border-color: #dc3545;
    }
    
    .is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
</style>
@endsection