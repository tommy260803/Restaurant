{{-- Paso 2: Fecha y Hora --}}
<div class="text-center mb-5">
    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-3" 
         style="width: 80px; height: 80px;">
        <i class="bi bi-calendar-heart text-primary" style="font-size: 2.5rem;"></i>
    </div>
    <h4 class="fw-bold mb-2">¿Cuándo nos visitas?</h4>
    <p class="text-muted">Selecciona tu fecha y hora preferida</p>
</div>

<!-- Selector de Fecha -->
<div class="mb-4">
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-4">
            <label class="form-label fw-bold d-flex align-items-center mb-3">
                <i class="bi bi-calendar3 text-primary me-2 fs-5"></i> 
                <span>Fecha de la reserva</span>
                <span class="badge bg-danger ms-2">*</span>
            </label>
            <input type="date" 
                   x-model="formData.fecha"
                   class="form-control form-control-lg border-2 text-center fw-semibold" 
                   :min="minDate"
                   style="font-size: 1.1rem; border-radius: 12px;"
                   required>
        </div>
    </div>
</div>

<!-- Selector de Hora -->
<div class="mb-4" x-show="formData.fecha" x-transition>
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-4">
            <label class="form-label fw-bold d-flex align-items-center mb-3">
                <i class="bi bi-clock text-primary me-2 fs-5"></i> 
                <span>Hora de la reserva</span>
                <span class="badge bg-danger ms-2">*</span>
            </label>

            <div class="row g-3">
                @php
                    $horarios = [];
                    for ($h = 11; $h <= 22; $h++) {
                        $horarios[] = sprintf('%02d:00', $h);
                        if($h < 22) $horarios[] = sprintf('%02d:30', $h);
                    }
                @endphp

                @foreach($horarios as $hora)
                <div class="col-6 col-md-4 col-lg-3">
                    <button type="button"
                            class="btn w-100 hora-btn"
                            :class="formData.hora === '{{ $hora }}' ? 'btn-primary active-hora' : 'btn-light border'"
                            @click="formData.hora = '{{ $hora }}'">
                        <i class="bi bi-clock me-1"></i>
                        <span class="fw-semibold">{{ date('g:i A', strtotime($hora)) }}</span>
                    </button>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Selector de Personas -->
<div class="mb-4" x-show="formData.hora" x-transition>
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-4">
            <label class="form-label fw-bold d-flex align-items-center mb-4">
                <i class="bi bi-people text-primary me-2 fs-5"></i> 
                <span>Número de personas</span>
                <span class="badge bg-danger ms-2">*</span>
            </label>

            <div class="d-flex align-items-center justify-content-center gap-4">
                <button type="button"
                        class="btn btn-outline-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                        style="width: 56px; height: 56px;"
                        @click="if(formData.personas > 1) formData.personas--"
                        :disabled="formData.personas <= 1">
                    <i class="bi bi-dash-lg fs-5"></i>
                </button>

                <div class="text-center px-4">
                    <div class="display-4 fw-bold text-primary" x-text="formData.personas" style="line-height: 1;"></div>
                    <div class="badge bg-light text-primary border px-3 py-2 mt-2 fw-semibold" 
                         x-text="formData.personas === 1 ? 'persona' : 'personas'"></div>
                </div>

                <button type="button"
                        class="btn btn-outline-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                        style="width: 56px; height: 56px;"
                        @click="if(formData.personas < 20) formData.personas++"
                        :disabled="formData.personas >= 20">
                    <i class="bi bi-plus-lg fs-5"></i>
                </button>
            </div>

            <div class="text-center mt-3">
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i> 
                    Capacidad: 1 a 20 personas
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Resumen de selección -->
<div x-show="formData.fecha && formData.hora && formData.personas > 0" x-transition>
    <div class="alert alert-primary border-0 shadow-sm" style="border-radius: 16px;">
        <div class="d-flex align-items-start">
            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                <i class="bi bi-check-circle-fill text-primary fs-4"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="alert-heading fw-bold mb-2">
                    <i class="bi bi-calendar-check"></i> Resumen de tu reserva
                </h6>
                <div class="row g-2">
                    <div class="col-12 col-md-4">
                        <small class="text-muted d-block">Fecha</small>
                        <strong x-text="formatFecha(formData.fecha)"></strong>
                    </div>
                    <div class="col-6 col-md-4">
                        <small class="text-muted d-block">Hora</small>
                        <strong x-text="formData.hora"></strong>
                    </div>
                    <div class="col-6 col-md-4">
                        <small class="text-muted d-block">Personas</small>
                        <strong>
                            <span x-text="formData.personas"></span> 
                            <span x-text="formData.personas === 1 ? 'persona' : 'personas'"></span>
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hora-btn {
    padding: 1rem;
    font-size: 0.95rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 12px;
    border-width: 2px;
    background: white;
}

.hora-btn:hover:not(:disabled) {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(13, 110, 253, 0.15);
    border-color: #0d6efd;
}

.hora-btn.active-hora {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    border-color: #0d6efd;
    color: white;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08) !important;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.btn-outline-primary:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

.btn-outline-primary:not(:disabled):hover {
    transform: scale(1.1);
}

.rounded-circle {
    transition: all 0.3s ease;
}

.alert {
    animation: slideInUp 0.4s ease;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>