<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Nueva Reserva - Restaurant</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
        }

        .step.completed .step-circle {
            background: #28a745;
            color: white;
            border-color: #28a745;
        }

        .step-label {
            margin-top: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #6c757d;
        }

        .step.active .step-label {
            color: #0d6efd;
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

        /* Person Select Buttons */
        .person-select-btn {
            padding: 15px;
            min-height: 80px;
        }

        /* Mesa Cards */
        .mesa-card {
            padding: 20px;
            border: 3px solid #dee2e6;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            min-height: 150px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .mesa-card.disponible {
            border-color: #28a745;
            background: #d4edda;
        }

        .mesa-card.reservada {
            border-color: #ffc107;
            background: #fff3cd;
        }

        .mesa-card.ocupada {
            border-color: #dc3545;
            background: #f8d7da;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .mesa-card.selected {
            border-color: #0d6efd;
            background: #cfe2ff;
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(13, 110, 253, 0.5);
        }

        .mesa-card:hover:not(.ocupada) {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .mesa-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .mesa-number {
            font-size: 1.5rem;
            font-weight: bold;
        }

        /* Plato Cards */
        .plato-card {
            transition: all 0.3s ease;
        }

        .plato-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .quantity-control {
            display: flex;
            align-items: center;
        }

        /* Transitions */
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-5" x-data="reservaWizard()" x-cloak>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <!-- Título Principal -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary">
                    <i class="fas fa-calendar-check me-3"></i>Nueva Reserva
                </h1>
                <p class="text-muted fs-5">Completa el proceso en 6 sencillos pasos</p>
            </div>

            <!-- Barra de Progreso -->
            @include('reservas.components.progress')

            <!-- Formulario Principal -->
            <form @submit.prevent="submitForm">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        
                        <!-- PASO 1: Datos Personales -->
                        <div x-show="currentStep === 1" x-transition>
                            @include('reservas.components.step_datos')
                        </div>

                        <!-- PASO 2: Fecha y Hora -->
                        <div x-show="currentStep === 2" x-transition>
                            @include('reservas.components.step_fecha')
                        </div>

                        <!-- PASO 3: Selección de Mesa -->
                        <div x-show="currentStep === 3" x-transition>
                            @include('reservas.components.step_mesa')
                        </div>

                        <!-- PASO 4: Pre-orden (Opcional) -->
                        <div x-show="currentStep === 4" x-transition>
                            @include('reservas.components.step_menu')
                        </div>

                        <!-- PASO 5: Confirmación -->
                        <div x-show="currentStep === 5" x-transition>
                            @include('reservas.components.step_resumen')
                        </div>
                        
                        <!-- PASO 6: Pago -->
                        <div x-show="currentStep === 6" x-transition>
                            @include('reservas.components.step_pago')
                        </div>

                    </div>

                    <!-- Navegación -->
                    <div x-show="errorMessage" class="p-3">
                        <div class="alert alert-danger" x-text="errorMessage"></div>
                    </div>
                    
                    <div class="card-footer bg-light py-4">
                        <div class="d-flex justify-content-between">
                            <button type="button" 
                                    class="btn btn-outline-secondary btn-lg px-5"
                                    @click="prevStep"
                                    x-show="currentStep > 1">
                                <i class="fas fa-arrow-left me-2"></i>Atrás
                            </button>
                            
                            <div class="ms-auto">
                                <button type="button" 
                                        class="btn btn-primary btn-lg px-5"
                                        @click="nextStep"
                                        x-show="currentStep < 6">
                                    Siguiente<i class="fas fa-arrow-right ms-2"></i>
                                </button>
                                
                                <button type="submit" 
                                        class="btn btn-success btn-lg px-5"
                                        x-show="currentStep === 6"
                                        :disabled="procesandoPago || !aceptaTerminos || !formData.numero_operacion">
                                    <template x-if="!procesandoPago">
                                        <span><i class="fas fa-check-circle me-2"></i>Confirmar Reserva</span>
                                    </template>
                                    <template x-if="procesandoPago">
                                        <span>
                                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                            Procesando pago...
                                        </span>
                                    </template>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Alpine.js Data Function - DEBE IR ANTES de Alpine.js -->
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('reservaWizard', () => ({
        currentStep: 1,
        aceptaTerminos: false,
        minDate: new Date().toISOString().split('T')[0],
        formData: {
            nombre: '',
            telefono: '',
            email: '',
            fecha: '',
            hora: '',
            personas: 2,
            mesa_id: null,
            platos: [],
            notas: '',
            metodo_pago: 'yape',
            numero_operacion: ''
        },
        procesandoPago: false,
        errorMessage: null,
        
        init() {
            this.loadFromLocalStorage();
            this.$watch('formData', () => {
                this.saveToLocalStorage();
            });
        },
        
        nextStep() {
            if (this.validateStep()) {
                if (this.currentStep < 6) {
                    this.currentStep++;
                }
            }
        },
        
        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
            }
        },
        
        validateStep() {
            switch(this.currentStep) {
                case 1:
                    if (!this.formData.nombre || !this.formData.telefono || !this.formData.email) {
                        alert('Por favor completa todos los campos obligatorios');
                        return false;
                    }
                    if (!this.validateEmail(this.formData.email)) {
                        alert('Por favor ingresa un email válido');
                        return false;
                    }
                    break;
                    
                case 2:
                    if (!this.formData.fecha || !this.formData.hora || this.formData.personas === 0) {
                        alert('Por favor completa fecha, hora y número de personas');
                        return false;
                    }
                    break;
                    
                case 3:
                    if (!this.formData.mesa_id) {
                        alert('Por favor selecciona una mesa');
                        return false;
                    }
                    break;
            }
            return true;
        },
        
        validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        },
        
        incrementPlato(id, nombre, precio) {
            const plato = this.formData.platos.find(p => p.id === id);
            if (plato) {
                plato.cantidad++;
            } else {
                this.formData.platos.push({
                    id: id,
                    nombre: nombre,
                    precio: precio,
                    cantidad: 1
                });
            }
        },
        
        decrementPlato(id) {
            const plato = this.formData.platos.find(p => p.id === id);
            if (plato) {
                plato.cantidad--;
                if (plato.cantidad === 0) {
                    this.formData.platos = this.formData.platos.filter(p => p.id !== id);
                }
            }
        },
        
        getPlatoCantidad(id) {
            const plato = this.formData.platos.find(p => p.id === id);
            return plato ? plato.cantidad : 0;
        },
        
        calcularSubtotal() {
            let total = this.formData.platos.reduce((total, plato) => {
                return total + (plato.cantidad * plato.precio);
            }, 0);
            
            // Si no hay platos, cobrar monto mínimo de reserva (ejemplo: S/ 20)
            if (total === 0) {
                total = 20.00;
            }
            
            return total;
        },
        
        getMesaNumero() {
            return this.formData.mesa_id || '-';
        },
        
        formatFecha(fecha) {
            if (!fecha) return '';
            const date = new Date(fecha + 'T00:00:00');
            const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('es-ES', opciones);
        },
        
        saveToLocalStorage() {
            localStorage.setItem('reserva_draft', JSON.stringify(this.formData));
        },
        
        loadFromLocalStorage() {
            const saved = localStorage.getItem('reserva_draft');
            if (saved) {
                try {
                    this.formData = JSON.parse(saved);
                } catch(e) {
                    console.error('Error cargando datos guardados:', e);
                }
            }
        },
        
        async submitForm() {
            this.errorMessage = null;

            if (!this.aceptaTerminos) {
                this.errorMessage = 'Debes aceptar los términos y condiciones';
                return;
            }

            if (!this.formData.numero_operacion) {
                this.errorMessage = 'Por favor ingresa el número de operación de Yape';
                return;
            }

            // Preparar payload
            const payload = {
                ...this.formData,
                monto_total: this.calcularSubtotal()
            };

            this.procesandoPago = true;

            try {
                const response = await fetch('{{ route("reservas.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                if (!response.ok) {
                    const data = await response.json().catch(() => ({}));
                    this.errorMessage = data.message || 'Ocurrió un error procesando el pago. Por favor reintenta.';
                    this.procesandoPago = false;
                    return;
                }

                const data = await response.json();
                // Limpiar draft guardado
                localStorage.removeItem('reserva_draft');
                // Redirigir a la página de confirmación
                window.location.href = '/reservas/confirmacion/' + data.reserva_id;

            } catch (err) {
                console.error(err);
                this.errorMessage = 'No se pudo conectar con el servidor. Por favor reintenta.';
                this.procesandoPago = false;
            }
        }
    }))
});
</script>

<!-- Alpine.js - Cargar DESPUÉS de definir Alpine.data -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>
</html>