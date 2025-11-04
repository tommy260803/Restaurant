<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva Confirmada</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
/* Animación del Check de Éxito */
.success-checkmark {
    width: 120px;
    height: 120px;
    margin: 0 auto;
}

.check-icon {
    width: 120px;
    height: 120px;
    position: relative;
    border-radius: 50%;
    box-sizing: content-box;
    border: 4px solid #28a745;
}

.check-icon::before {
    top: 3px;
    left: -2px;
    width: 30px;
    transform-origin: 100% 50%;
    border-radius: 100px 0 0 100px;
}

.check-icon::after {
    top: 0;
    left: 30px;
    width: 60px;
    transform-origin: 0 50%;
    border-radius: 0 100px 100px 0;
    animation: rotate-circle 4.25s ease-in;
}

.icon-line {
    height: 5px;
    background-color: #28a745;
    display: block;
    border-radius: 2px;
    position: absolute;
    z-index: 10;
}

.icon-line.line-tip {
    top: 56px;
    left: 25px;
    width: 25px;
    transform: rotate(45deg);
    animation: icon-line-tip 0.75s;
}

.icon-line.line-long {
    top: 48px;
    right: 15px;
    width: 47px;
    transform: rotate(-45deg);
    animation: icon-line-long 0.75s;
}

.icon-circle {
    top: -4px;
    left: -4px;
    z-index: 10;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    position: absolute;
    box-sizing: content-box;
    border: 4px solid rgba(40, 167, 69, .5);
}

.icon-fix {
    top: 12px;
    width: 10px;
    left: 34px;
    z-index: 1;
    height: 90px;
    position: absolute;
    transform: rotate(-45deg);
    background-color: #fff;
}

@keyframes rotate-circle {
    0% { transform: rotate(-45deg); }
    5% { transform: rotate(-45deg); }
    12% { transform: rotate(-405deg); }
    100% { transform: rotate(-405deg); }
}

@keyframes icon-line-tip {
    0% { width: 0; left: 1px; top: 19px; }
    54% { width: 0; left: 1px; top: 19px; }
    70% { width: 50px; left: -8px; top: 37px; }
    84% { width: 17px; left: 21px; top: 48px; }
    100% { width: 25px; left: 14px; top: 45px; }
}

@keyframes icon-line-long {
    0% { width: 0; right: 46px; top: 54px; }
    65% { width: 0; right: 46px; top: 54px; }
    84% { width: 55px; right: 0px; top: 35px; }
    100% { width: 47px; right: 8px; top: 38px; }
}

/* Animaciones de Entrada */
.animate-fade-in {
    animation: fadeIn 1s ease-in;
}

.animate-fade-in-delay {
    animation: fadeIn 1s ease-in 0.3s both;
}

.animate-fade-in-delay-2 {
    animation: fadeIn 1s ease-in 0.6s both;
}

.animate-fade-in-delay-3 {
    animation: fadeIn 1s ease-in 0.9s both;
}

.animate-slide-up {
    animation: slideUp 0.8s ease-out;
}

.animate-slide-up-delay {
    animation: slideUp 0.8s ease-out 0.2s both;
}

.animate-slide-up-delay-2 {
    animation: slideUp 0.8s ease-out 0.4s both;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Código de Confirmación */
.confirmation-code {
    display: inline-block;
}

.code-display {
    font-size: 2rem;
    font-weight: bold;
    font-family: 'Courier New', monospace;
    color: #28a745;
    background: #d4edda;
    padding: 10px 30px;
    border-radius: 10px;
    border: 2px dashed #28a745;
    letter-spacing: 3px;
}

/* Card Gradient */
.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

/* QR Container */
.qr-container {
    position: relative;
}

.sticky-qr {
    position: sticky;
    top: 20px;
}

.qr-wrapper {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.qr-code-box {
    background: white;
    padding: 15px;
    border-radius: 10px;
    display: inline-block;
}

/* Botones de Acción */
.btn-action {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
    transition: all 0.3s ease;
}

.btn-action:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.btn-action i {
    font-size: 1.2rem;
}

/* Instrucciones */
.instruction-item {
    display: flex;
    align-items: start;
    margin-bottom: 20px;
}

.instruction-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
    flex-shrink: 0;
    margin-right: 15px;
}

.instruction-text {
    flex: 1;
    padding-top: 5px;
}

.instruction-text strong {
    display: block;
    margin-bottom: 5px;
    color: #212529;
}

/* HR Dashed */
.dashed-hr {
    border-top: 2px dashed #dee2e6;
    margin: 10px 0;
}

/* Social Share */
.social-share .btn {
    width: 45px;
    height: 45px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.social-share .btn:hover {
    transform: scale(1.1);
}

/* Canvas Confeti */
#confetti-canvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 9999;
}
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <!-- Animación de Éxito -->
            <div class="text-center mb-5 success-animation">
                <div class="success-checkmark">
                    <div class="check-icon">
                        <span class="icon-line line-tip"></span>
                        <span class="icon-line line-long"></span>
                        <div class="icon-circle"></div>
                        <div class="icon-fix"></div>
                    </div>
                </div>
                
                <h1 class="display-4 fw-bold text-success mt-4 animate-fade-in">
                    ¡Reserva Confirmada!
                </h1>
                <p class="lead text-muted animate-fade-in-delay">
                    Tu mesa está reservada y lista para ti
                </p>
                
                <!-- Código de Confirmación -->
                <div class="confirmation-code mt-4 animate-fade-in-delay-2">
                    <span class="text-muted small d-block mb-2">Código de confirmación</span>
                    <span class="code-display">{{ $reserva->codigo_confirmacion }}</span>
                </div>
            </div>

            <!-- Card Principal -->
            <div class="card shadow-lg border-0 mb-4 animate-slide-up">
                <div class="card-header bg-gradient-success text-white py-3">
                    <h4 class="mb-0 text-center">
                        <i class="fas fa-clipboard-list me-2"></i>Detalles de tu Reserva
                    </h4>
                </div>
                
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Columna Izquierda: Información -->
                        <div class="col-md-7">
                            
                            <!-- Datos de Contacto -->
                            <div class="mb-4 pb-3 border-bottom">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-user-circle me-2 text-primary"></i>Datos de contacto
                                </h5>
                                <div class="ps-3">
                                    <p class="mb-2 fs-5 fw-semibold">{{ $reserva->nombre }}</p>
                                    <p class="mb-2 text-muted">
                                        <i class="fas fa-phone me-2"></i>{{ $reserva->telefono }}
                                    </p>
                                    <p class="mb-0 text-muted">
                                        <i class="fas fa-envelope me-2"></i>{{ $reserva->email }}
                                    </p>
                                </div>
                            </div>

                            <!-- Fecha y Hora -->
                            <div class="mb-4 pb-3 border-bottom">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-calendar-alt me-2 text-success"></i>Fecha y hora
                                </h5>
                                <div class="ps-3">
                                    <p class="mb-2 fs-5 fw-semibold">
                                        {{ \Carbon\Carbon::parse($reserva->fecha)->locale('es')->isoFormat('dddd D [de] MMMM, YYYY') }}
                                    </p>
                                    <p class="mb-0 text-muted">
                                        <i class="fas fa-clock me-2"></i>{{ \Carbon\Carbon::parse($reserva->hora)->format('g:i A') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Mesa -->
                            <div class="mb-4 pb-3 border-bottom">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-chair me-2 text-warning"></i>Mesa reservada
                                </h5>
                                <div class="ps-3">
                                    <p class="mb-2 fs-5 fw-semibold">
                                        Mesa #{{ $reserva->mesa->numero }} - {{ $reserva->mesa->ubicacion }}
                                    </p>
                                    <p class="mb-0 text-muted">
                                        <i class="fas fa-users me-2"></i>{{ $reserva->personas }} {{ $reserva->personas == 1 ? 'persona' : 'personas' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Pre-orden -->
                            @if(isset($reserva->pedidos) && $reserva->pedidos->count() > 0)
                            <div class="mb-4 pb-3 border-bottom">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-utensils me-2 text-danger"></i>Pre-orden
                                </h5>
                                <div class="ps-3">
                                    @php $subtotal = 0; @endphp
                                    @foreach($reserva->pedidos as $item)
                                        @php 
                                            $cantidad = $item->pivot->cantidad ?? 0;
                                            $precio = $item->pivot->precio ?? 0;
                                            $totalItem = $cantidad * $precio;
                                            $subtotal += $totalItem;
                                        @endphp
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>{{ $cantidad }}x {{ $item->nombre }}</span>
                                            <span class="fw-semibold">S/ {{ number_format($totalItem, 2) }}</span>
                                        </div>
                                    @endforeach
                                    <hr class="dashed-hr">
                                    <div class="d-flex justify-content-between">
                                        <strong>Subtotal:</strong>
                                        <strong class="text-success">S/ {{ number_format($subtotal, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Notas -->
                            @if($reserva->notas)
                            <div class="mb-3">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-comment-dots me-2 text-info"></i>Notas especiales
                                </h5>
                                <div class="ps-3">
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-quote-left me-2"></i>
                                        {{ $reserva->notas }}
                                        <i class="fas fa-quote-right ms-2"></i>
                                    </div>
                                </div>
                            </div>
                            @endif

                        </div>

                        <!-- Columna Derecha: QR Code -->
                        <div class="col-md-5">
                            <div class="qr-container text-center sticky-qr">
                                <div class="qr-wrapper p-4 bg-light rounded">
                                    <h5 class="mb-3 fw-bold">Código QR</h5>
                                    <div class="qr-code-box">
                                        {!! QrCode::size(200)->generate($reserva->codigo_confirmacion) !!}
                                    </div>
                                    <p class="text-muted small mt-3 mb-0">
                                        Presenta este código al llegar al restaurante
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="card shadow-sm border-0 mb-4 animate-slide-up-delay">
                <div class="card-body p-4">
                    <h5 class="mb-4 text-center fw-bold">
                        <i class="fas fa-tools me-2"></i>Acciones Rápidas
                    </h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('reserva.pdf', $reserva->id) }}" 
                               class="btn btn-outline-danger btn-lg w-100 btn-action">
                                <i class="fas fa-file-pdf me-2"></i>
                                <span>Descargar PDF</span>
                            </a>
                        </div>
                        
                        <div class="col-md-6">
                            <form action="{{ route('reserva.reenviar-email', $reserva->id) }}" method="POST" class="d-inline w-100">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-lg w-100 btn-action">
                                    <i class="fas fa-envelope me-2"></i>
                                    <span>Reenviar Email</span>
                                </button>
                            </form>
                        </div>
                        
                        <div class="col-md-6">
                            <a href="{{ route('reserva.google-calendar', $reserva->id) }}" 
                               target="_blank"
                               class="btn btn-outline-success btn-lg w-100 btn-action">
                                <i class="fab fa-google me-2"></i>
                                <span>Agregar a Calendar</span>
                            </a>
                        </div>
                        
                        <div class="col-md-6">
                            <a href="{{ route('home') }}" 
                               class="btn btn-outline-dark btn-lg w-100 btn-action">
                                <i class="fas fa-home me-2"></i>
                                <span>Volver al Inicio</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instrucciones Importantes -->
            <div class="card shadow-sm border-warning border-2 animate-slide-up-delay-2">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información Importante
                    </h5>
                </div>
                <div class="card-body">
                    <div class="instruction-item">
                        <div class="instruction-icon bg-primary">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <div class="instruction-text">
                            <strong>Confirmación por Email</strong>
                            <p class="mb-0 text-muted">Te enviamos un correo con todos los detalles de tu reserva a <strong>{{ $reserva->email }}</strong></p>
                        </div>
                    </div>

                    <div class="instruction-item">
                        <div class="instruction-icon bg-success">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <div class="instruction-text">
                            <strong>Código QR</strong>
                            <p class="mb-0 text-muted">Presenta este código QR o tu código de confirmación al llegar al restaurante</p>
                        </div>
                    </div>

                    <div class="instruction-item">
                        <div class="instruction-icon bg-info">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="instruction-text">
                            <strong>Tiempo de Llegada</strong>
                            <p class="mb-0 text-muted">Por favor llega 10 minutos antes de tu hora reservada. Te esperamos hasta 15 minutos después</p>
                        </div>
                    </div>

                    <div class="instruction-item mb-0">
                        <div class="instruction-icon bg-danger">
                            <i class="fas fa-ban"></i>
                        </div>
                        <div class="instruction-text">
                            <strong>Política de Cancelación</strong>
                            <p class="mb-0 text-muted">Puedes cancelar o modificar tu reserva hasta 2 horas antes sin costo alguno</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Compartir en Redes -->
            <div class="text-center mt-4 animate-fade-in-delay-3">
                <p class="text-muted mb-3">Comparte tu experiencia:</p>
                <div class="social-share">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" 
                       target="_blank" 
                       class="btn btn-outline-primary btn-sm rounded-circle me-2">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=¡Acabo de hacer mi reserva!" 
                       target="_blank" 
                       class="btn btn-outline-info btn-sm rounded-circle me-2">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://api.whatsapp.com/send?text=¡Mira mi reserva!" 
                       target="_blank" 
                       class="btn btn-outline-success btn-sm rounded-circle">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Canvas para Confeti -->
<canvas id="confetti-canvas"></canvas>

<script>
// Efecto de Confeti
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('confetti-canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    const confetti = [];
    const confettiCount = 150;
    const gravity = 0.5;
    const terminalVelocity = 5;
    const drag = 0.075;
    const colors = [
        { front: '#28a745', back: '#20c997' },
        { front: '#ffc107', back: '#fd7e14' },
        { front: '#dc3545', back: '#e83e8c' },
        { front: '#007bff', back: '#6610f2' },
    ];

    function randomRange(min, max) {
        return Math.random() * (max - min) + min;
    }

    function initConfetti() {
        for (let i = 0; i < confettiCount; i++) {
            confetti.push({
                color: colors[Math.floor(randomRange(0, colors.length))],
                dimensions: {
                    x: randomRange(10, 20),
                    y: randomRange(10, 30),
                },
                position: {
                    x: randomRange(0, canvas.width),
                    y: canvas.height - 1,
                },
                rotation: randomRange(0, 2 * Math.PI),
                scale: {
                    x: 1,
                    y: 1,
                },
                velocity: {
                    x: randomRange(-25, 25),
                    y: randomRange(0, -50),
                },
            });
        }
    }

    function render() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        confetti.forEach((confetto, index) => {
            let width = confetto.dimensions.x * confetto.scale.x;
            let height = confetto.dimensions.y * confetto.scale.y;

            ctx.translate(confetto.position.x, confetto.position.y);
            ctx.rotate(confetto.rotation);

            confetto.velocity.x -= confetto.velocity.x * drag;
            confetto.velocity.y = Math.min(confetto.velocity.y + gravity, terminalVelocity);
            confetto.velocity.x += Math.random() > 0.5 ? Math.random() : -Math.random();

            confetto.position.x += confetto.velocity.x;
            confetto.position.y += confetto.velocity.y;

            if (confetto.position.y >= canvas.height) confetti.splice(index, 1);

            if (confetto.position.x > canvas.width) confetto.position.x = 0;
            if (confetto.position.x < 0) confetto.position.x = canvas.width;

            confetto.scale.y = Math.cos(confetto.position.y * 0.1);
            ctx.fillStyle = confetto.scale.y > 0 ? confetto.color.front : confetto.color.back;

            ctx.fillRect(-width / 2, -height / 2, width, height);

            ctx.setTransform(1, 0, 0, 1, 0, 0);
        });

        if (confetti.length > 0) {
            window.requestAnimationFrame(render);
        } else {
            canvas.remove();
        }
    }

    initConfetti();
    render();

    // Detener confeti después de 5 segundos
    setTimeout(() => {
        confetti.length = 0;
    }, 5000);
});

// Copiar código al hacer click
document.querySelector('.code-display')?.addEventListener('click', function() {
    const code = this.textContent;
    navigator.clipboard.writeText(code).then(() => {
        const original = this.textContent;
        this.textContent = '✓ Copiado!';
        setTimeout(() => {
            this.textContent = original;
        }, 2000);
    });
});
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>