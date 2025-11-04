{{-- Paso 6: Pago --}}
<h4 class="mb-4">
    <i class="fas fa-credit-card me-2"></i>Método de Pago
</h4>

<!-- Resumen del Total -->
<div class="alert alert-info mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="fs-5"><strong>Total a pagar:</strong></span>
        <span class="fs-4 fw-bold">S/ <span x-text="calcularSubtotal().toFixed(2)"></span></span>
    </div>
    <small class="text-muted" x-show="formData.platos.length === 0">
        * Monto de garantía de reserva
    </small>
</div>

<!-- Métodos de Pago -->
<div class="mb-4">
    <label class="form-label fw-bold">Método de pago:</label>
    
    <div class="row g-3 justify-content-center">
        <!-- Yape -->
        <div class="col-md-6">
            <div class="card border-2 border-primary" style="cursor: pointer;">
                <div class="card-body text-center">
                    <input type="radio" 
                           name="metodo_pago" 
                           value="yape" 
                           x-model="formData.metodo_pago"
                           class="form-check-input me-2"
                           checked>
                    <i class="fas fa-mobile-alt fa-3x text-success mb-3"></i>
                    <h6>Yape</h6>
                    <small class="text-muted">Pago inmediato con código QR</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Información adicional según método de pago -->
<div class="mt-4">
    <!-- Info Yape -->
    <div class="alert alert-light border">
        <h6><i class="fas fa-info-circle me-2"></i>Instrucciones de pago</h6>
        <p class="mb-2">Escanea el código QR con tu app de Yape:</p>
        <div class="text-center">
            <img src="{{ asset('img/yape.jpg') }}" alt="QR Yape" style="max-width: 200px;" class="img-fluid">
        </div>
        
        <!-- Campo número de operación -->
        <div class="mt-3">
            <label class="form-label fw-bold">Número de Operación Yape <span class="text-danger">*</span></label>
            <input type="text" 
                   class="form-control" 
                   x-model="formData.numero_operacion"
                   placeholder="Ej: 123456789"
                   maxlength="15"
                   required>
            <small class="text-muted">Ingresa el número que aparece en tu comprobante de Yape después de realizar el pago</small>
        </div>
    </div>
</div>

<!-- Términos y Condiciones -->
<div class="mt-4">
    <div class="form-check">
        <input type="checkbox" 
               class="form-check-input" 
               id="aceptaTerminos" 
               x-model="aceptaTerminos">
        <label class="form-check-label" for="aceptaTerminos">
            Acepto los <a href="#" class="text-primary">términos y condiciones</a> de la reserva
        </label>
    </div>
</div>

<!-- Validación de método de pago -->
<div x-show="!formData.numero_operacion" class="alert alert-danger mt-3">
    <i class="fas fa-exclamation-circle me-2"></i>
    Por favor ingresa el número de operación de Yape para continuar
</div>
