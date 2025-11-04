{{-- Paso 5: Resumen Final --}}
<h4 class="mb-4 text-center">
    <i class="bi bi-clipboard-check"></i> Confirma tu Reserva
</h4>
<p class="text-center text-muted mb-4">Revisa todos los detalles antes de confirmar</p>

<!-- Resumen Completo -->
<div class="border rounded p-4 mb-4">
    <!-- Datos Personales -->
    <div class="mb-3 pb-3 border-bottom">
        <h6 class="fw-bold text-primary mb-2">
            <i class="bi bi-person-circle"></i> Datos de Contacto
        </h6>
        <div class="ps-3">
            <p class="mb-1"><strong>Nombre:</strong> <span x-text="formData.nombre"></span></p>
            <p class="mb-1"><strong>Teléfono:</strong> <span x-text="formData.telefono"></span></p>
            <p class="mb-0" x-show="formData.email">
                <strong>Email:</strong> <span x-text="formData.email"></span>
            </p>
        </div>
    </div>

    <!-- Fecha y Hora -->
    <div class="mb-3 pb-3 border-bottom">
        <h6 class="fw-bold text-success mb-2">
            <i class="bi bi-calendar-check"></i> Fecha y Hora
        </h6>
        <div class="ps-3">
            <p class="mb-1">
                <strong>Fecha:</strong> <span x-text="formatFecha(formData.fecha)"></span>
            </p>
            <p class="mb-0">
                <strong>Hora:</strong> <span x-text="formData.hora"></span>
            </p>
        </div>
    </div>

    <!-- Mesa -->
    <div class="mb-3 pb-3 border-bottom">
        <h6 class="fw-bold text-warning mb-2">
            <i class="bi bi-table"></i> Mesa Seleccionada
        </h6>
        <div class="ps-3">
            <p class="mb-1">
                <strong>Mesa:</strong> #<span x-text="formData.mesa_id"></span>
            </p>
            <p class="mb-0">
                <strong>Personas:</strong> <span x-text="formData.personas"></span>
            </p>
        </div>
    </div>

    <!-- Pre-orden (si existe) -->
    <div class="mb-3" x-show="formData.platos.length > 0">
        <h6 class="fw-bold text-info mb-2">
            <i class="bi bi-basket"></i> Pre-orden
        </h6>
        <div class="ps-3">
            <template x-for="plato in formData.platos" :key="plato.id">
                <div class="d-flex justify-content-between mb-2">
                    <span>
                        <span x-text="plato.cantidad"></span>x 
                        <span x-text="plato.nombre"></span>
                    </span>
                    <span class="text-primary">S/ <span x-text="(plato.cantidad * plato.precio).toFixed(2)"></span></span>
                </div>
            </template>
            <hr class="my-2">
            <div class="d-flex justify-content-between fw-bold">
                <span>Subtotal:</span>
                <span class="text-success">S/ <span x-text="calcularSubtotal().toFixed(2)"></span></span>
            </div>
        </div>
    </div>

    <!-- Notas Especiales -->
    <div x-show="formData.notas">
        <h6 class="fw-bold text-secondary mb-2">
            <i class="bi bi-chat-left-text"></i> Notas Especiales
        </h6>
        <div class="ps-3">
            <p class="mb-0 fst-italic" x-text="formData.notas"></p>
        </div>
    </div>
</div>

<!-- Notas Especiales (campo de entrada) -->
<div class="mb-4">
    <label class="form-label fw-bold">
        <i class="bi bi-chat-left-text"></i> Notas Especiales (opcional)
    </label>
    <textarea x-model="formData.notas"
              class="form-control"
              rows="3"
              placeholder="Ejemplo: Es cumpleaños, necesito velas. Alergias, preferencias, etc."></textarea>
</div>

<div class="alert alert-info">
    <i class="bi bi-info-circle"></i>
    <strong>Siguiente paso:</strong> En la siguiente pantalla podrás seleccionar tu método de pago preferido.
</div>

