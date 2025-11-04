{{-- Barra de progreso dinámica con Alpine.js --}}
<div class="card shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="progress-steps">
            <!-- Paso 1: Datos -->
            <div class="step" :class="{ 'active': currentStep >= 1, 'completed': currentStep > 1 }">
                <div class="step-circle">
                    <i class="fas fa-user"></i>
                </div>
                <div class="step-label">Datos</div>
            </div>
            <div class="step-line" :class="{ 'completed': currentStep > 1 }"></div>
            
            <!-- Paso 2: Fecha -->
            <div class="step" :class="{ 'active': currentStep >= 2, 'completed': currentStep > 2 }">
                <div class="step-circle">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="step-label">Fecha</div>
            </div>
            <div class="step-line" :class="{ 'completed': currentStep > 2 }"></div>
            
            <!-- Paso 3: Mesa -->
            <div class="step" :class="{ 'active': currentStep >= 3, 'completed': currentStep > 3 }">
                <div class="step-circle">
                    <i class="fas fa-chair"></i>
                </div>
                <div class="step-label">Mesa</div>
            </div>
            <div class="step-line" :class="{ 'completed': currentStep > 3 }"></div>
            
            <!-- Paso 4: Menú -->
            <div class="step" :class="{ 'active': currentStep >= 4, 'completed': currentStep > 4 }">
                <div class="step-circle">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="step-label">Menú</div>
            </div>
            <div class="step-line" :class="{ 'completed': currentStep > 4 }"></div>
            
            <!-- Paso 5: Resumen -->
            <div class="step" :class="{ 'active': currentStep >= 5, 'completed': currentStep > 5 }">
                <div class="step-circle">
                    <i class="fas fa-check"></i>
                </div>
                <div class="step-label">Confirmar</div>
            </div>
            <div class="step-line" :class="{ 'completed': currentStep > 5 }"></div>

            <!-- Paso 6: Pago -->
            <div class="step" :class="{ 'active': currentStep >= 6 }">
                <div class="step-circle">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="step-label">Pago</div>
            </div>
        </div>
    </div>
</div>

<style>
/* Progress Steps */
.progress-steps {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 0 0 auto;
}

.step-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #6c757d;
    transition: all 0.3s ease;
    border: 3px solid #e9ecef;
}

.step.active .step-circle {
    background: #0d6efd;
    color: white;
    border-color: #0d6efd;
    transform: scale(1.1);
    animation: pulse 2s infinite;
}

.step.completed .step-circle {
    background: #28a745;
    color: white;
    border-color: #28a745;
}

@keyframes pulse {
    0%, 100% {
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.2);
    }
    50% {
        box-shadow: 0 0 0 8px rgba(13, 110, 253, 0.1);
    }
}

.step-label {
    margin-top: 10px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #6c757d;
    text-align: center;
}

.step.active .step-label {
    color: #0d6efd;
}

.step.completed .step-label {
    color: #28a745;
}

.step-line {
    flex: 1;
    height: 3px;
    background: #e9ecef;
    margin: 0 10px;
    transition: all 0.3s ease;
}

.step-line.completed {
    background: #28a745;
}

/* Responsive */
@media (max-width: 768px) {
    .step-circle {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
    
    .step-label {
        font-size: 0.75rem;
        margin-top: 8px;
    }
}

@media (max-width: 576px) {
    .step-circle {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .step-label {
        font-size: 0.7rem;
        margin-top: 6px;
    }
    
    .step-line {
        margin: 0 5px;
    }
}
</style>