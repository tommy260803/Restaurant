{{-- Paso 1: Datos de Contacto --}}
<div class="text-center mb-5">
    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-3" 
         style="width: 80px; height: 80px;">
        <i class="bi bi-person-circle text-primary" style="font-size: 2.5rem;"></i>
    </div>
    <h4 class="fw-bold mb-2">Datos de Contacto</h4>
    <p class="text-muted">Ingresa tus datos para confirmar tu reserva</p>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="form-floating">
            <input type="text" 
                   x-model="formData.nombre"
                   class="form-control form-control-lg border-2" 
                   id="nombre"
                   placeholder="Nombre completo"
                   required>
            <label for="nombre">
                <i class="bi bi-person me-2 text-primary"></i> Nombre Completo *
            </label>
        </div>
        <div x-show="formData.nombre.length > 0 && formData.nombre.length < 3" 
             class="form-text text-warning mt-2">
            <i class="bi bi-exclamation-triangle-fill"></i> Mínimo 3 caracteres
        </div>
    </div>

    <div class="col-12">
        <div class="form-floating">
            <input type="tel" 
                   x-model="formData.telefono"
                   class="form-control form-control-lg border-2" 
                   id="telefono"
                   placeholder="Teléfono"
                   maxlength="15"
                   required>
            <label for="telefono">
                <i class="bi bi-telephone me-2 text-primary"></i> Teléfono *
            </label>
        </div>
        <div class="form-text mt-2">
            <i class="bi bi-info-circle text-muted"></i> Formato: +51 999 999 999
        </div>
    </div>

    <div class="col-12">
        <div class="form-floating">
            <input type="email" 
                   x-model="formData.email"
                   class="form-control form-control-lg border-2" 
                   id="email"
                   placeholder="Email">
            <label for="email">
                <i class="bi bi-envelope me-2 text-primary"></i> Email
            </label>
        </div>
        <div class="form-text mt-2">
            <i class="bi bi-star-fill text-warning"></i> Recomendado para recibir confirmación
        </div>
    </div>
</div>

<style>
.form-control-lg {
    padding: 1rem 0.75rem;
    font-size: 1rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.form-floating > label {
    padding: 1rem 0.75rem;
}

.form-text {
    font-size: 0.875rem;
}
</style>