{{-- Paso 2: Fecha y Hora --}}
<h4 class="mb-4 text-center">
    <i class="bi bi-calendar-heart"></i> ¿Cuándo nos visitas?
</h4>
<p class="text-center text-muted mb-4">Selecciona tu fecha y hora preferida</p>

<!-- Selector de Fecha -->
<div class="mb-4">
    <label class="form-label fw-bold">
        <i class="bi bi-calendar3"></i> Fecha de la reserva *
    </label>
    <input type="date" 
           x-model="formData.fecha"
           class="form-control form-control-lg" 
           :min="minDate"
           required>
</div>

    <!-- Selector de Hora -->
    <div class="mb-4" x-show="formData.fecha">
        <label class="form-label fw-bold">
            <i class="bi bi-clock"></i> Hora de la reserva *
        </label>

        <div class="row g-2">
            @php
                $horarios = [];
                for ($h = 11; $h <= 22; $h++) {
                    $horarios[] = sprintf('%02d:00', $h);
                    $horarios[] = sprintf('%02d:30', $h);
                }
            @endphp

            @foreach($horarios as $hora)
            <div class="col-6 col-md-3">
                <button type="button"
                        class="btn w-100 hora-btn"
                        :class="{ 'btn-primary': formData.hora === '{{ $hora }}', 'btn-outline-secondary': formData.hora !== '{{ $hora }}' }"
                        @click="formData.hora = '{{ $hora }}'">
                    {{ date('g:i A', strtotime($hora)) }}
                </button>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Selector de Personas -->
    <div class="mb-4" x-show="formData.hora">
        <label class="form-label fw-bold">
            <i class="bi bi-people"></i> Número de personas *
        </label>

        <div class="d-flex align-items-center justify-content-center gap-3">
            <button type="button"
                    class="btn btn-outline-primary btn-lg"
                    @click="if(formData.personas > 1) formData.personas--"
                    :disabled="formData.personas <= 1">
                <i class="bi bi-dash-lg"></i>
            </button>

            <div class="text-center">
                <div class="display-4 fw-bold text-primary" x-text="formData.personas"></div>
                <small class="text-muted" x-text="formData.personas === 1 ? 'persona' : 'personas'"></small>
            </div>

            <button type="button"
                    class="btn btn-outline-primary btn-lg"
                    @click="if(formData.personas < 20) formData.personas++"
                    :disabled="formData.personas >= 20">
                <i class="bi bi-plus-lg"></i>
            </button>
        </div>
    </div>

    <!-- Resumen de selección -->
    <div class="alert alert-info" x-show="formData.fecha && formData.hora && formData.personas > 0">
        <i class="bi bi-info-circle"></i>
        <strong>Reserva para:</strong> 
        <span x-text="formatFecha(formData.fecha)"></span> a las 
        <span x-text="formData.hora"></span> para 
        <span x-text="formData.personas"></span>
        <span x-text="formData.personas === 1 ? 'persona' : 'personas'"></span>
    </div>
</div>

<style>
    .hora-btn {
        padding: 0.75rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        border-radius: 8px;
    }

    .hora-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>