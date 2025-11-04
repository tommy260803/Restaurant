{{-- Paso 1: Datos de Contacto --}}
<h4 class="mb-4 text-center">
    <i class="bi bi-person-circle"></i> Datos de Contacto
</h4>
<p class="text-center text-muted mb-4">Necesitamos tus datos para confirmar la reserva</p>

<div class="mb-3">
    <label class="form-label fw-bold">
        <i class="bi bi-person"></i> Nombre Completo *
    </label>
    <input type="text" 
           x-model="formData.nombre"
           class="form-control form-control-lg" 
           placeholder="Juan Pérez García"
           required>
    <small class="text-muted" x-show="formData.nombre.length > 0 && formData.nombre.length < 3">
        ⚠️ Mínimo 3 caracteres
    </small>
</div>

<div class="mb-3">
    <label class="form-label fw-bold">
        <i class="bi bi-telephone"></i> Teléfono *
    </label>
    <input type="tel" 
           x-model="formData.telefono"
           class="form-control form-control-lg" 
           placeholder="+51 999 999 999"
           maxlength="15"
           required>
    <small class="text-muted">Formato: +51 999 999 999</small>
</div>

<div class="mb-3">
    <label class="form-label fw-bold">
        <i class="bi bi-envelope"></i> Email (opcional)
    </label>
    <input type="email" 
           x-model="formData.email"
           class="form-control form-control-lg" 
           placeholder="tu@email.com">
    <small class="text-muted">Recomendado para recibir confirmación</small>
</div>