<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago - Delivery</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <style>
        :root {
            --peru-red: #b91c1c;
        }
        
        body {
            background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        
        .logo {
            height: 50px;
            width: 50px;
        }
        
        .main-container {
            padding: 2rem 0;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .card-header {
            border-radius: 15px 15px 0 0 !important;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }
        
        .btn-success {
            background: #16a34a;
            border: none;
            border-radius: 10px;
            padding: 0.8rem 1.5rem;
            font-weight: 600;
        }
        
        .btn-success:hover {
            background: #15803d;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--peru-red);
            box-shadow: 0 0 0 0.2rem rgba(185,28,28,0.15);
        }
    </style>
</head>
<body>
    <nav class="navbar-custom mb-4">
        <div class="container">
            <div class="d-flex align-items-center gap-3">
                <img src="/img/resta.png" alt="Logo" class="logo">
                <h4 class="mb-0 fw-bold" style="color: var(--peru-red);">Sabor Peruano</h4>
            </div>
        </div>
    </nav>

    <div class="container main-container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="text-center mb-4">
                    <h1 class="fw-bold" style="color: var(--peru-red); font-size: 2.5rem;">
                        <i class="ri-bank-card-line"></i> Confirmar Pago
                    </h1>
                    <p class="text-muted">Pedido #{{ $pedido->id }}</p>
                </div>

                <div class="row">
                    <!-- Resumen del Pedido -->
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header text-white" style="background: #0891b2;">
                                <h5 class="mb-0"><i class="ri-file-list-3-line"></i> Resumen del Pedido</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <h6 class="fw-bold" style="color: var(--peru-red);">
                                        <i class="ri-map-pin-line"></i> Datos de Entrega
                                    </h6>
                                    <p class="mb-1"><strong>Nombre:</strong> {{ $pedido->nombre_cliente }}</p>
                                    <p class="mb-1"><strong>Teléfono:</strong> {{ $pedido->telefono }}</p>
                                    <p class="mb-1"><strong>Dirección:</strong> {{ $pedido->direccion_entrega }}</p>
                                    @if($pedido->referencia)
                                        <p class="mb-1"><strong>Referencia:</strong> {{ $pedido->referencia }}</p>
                                    @endif
                                    <p class="mb-1">
                                        <strong>Fecha/Hora:</strong> 
                                        {{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y') }} 
                                        - {{ \Carbon\Carbon::parse($pedido->hora_pedido)->format('H:i') }}
                                    </p>
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <h6 class="fw-bold" style="color: var(--peru-red);">
                                        <i class="ri-restaurant-line"></i> Platos
                                    </h6>
                                    @foreach($pedido->platos as $item)
                                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                        <div>
                                            <strong>{{ $item->plato->nombre }}</strong> 
                                            <span class="text-muted">x{{ $item->cantidad }}</span>
                                            @if($item->notas)
                                                <br><small class="text-muted fst-italic">{{ $item->notas }}</small>
                                            @endif
                                        </div>
                                        <span class="fw-bold">S/. {{ number_format($item->precio * $item->cantidad, 2) }}</span>
                                    </div>
                                    @endforeach
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 fw-bold">TOTAL:</h5>
                                    <h3 class="mb-0 fw-bold" style="color: #16a34a;">S/. {{ number_format($total, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de Pago -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header text-white" style="background: #16a34a;">
                                <h5 class="mb-0"><i class="ri-wallet-3-line"></i> Método de Pago</h5>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('delivery.pago.store', $pedido->id) }}" method="POST">
                                    @csrf

                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Selecciona método de pago *</label>
                                        <select name="metodo" id="metodoPago" class="form-select form-select-lg @error('metodo') is-invalid @enderror" required>
                                            <option value="">-- Seleccionar --</option>
                                            <option value="yape">💸 Yape</option>
                                            <option value="plin">📱 Plin</option>
                                            <option value="transferencia">🏦 Transferencia</option>
                                            <option value="efectivo">💵 Efectivo</option>
                                        </select>
                                        @error('metodo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Información de Pago Digital -->
                                    <div id="infoPagoDigital" style="display: none;">
                                        <div class="alert alert-info">
                                            <h6 class="fw-bold mb-2">
                                                <i class="ri-smartphone-line"></i> Datos para transferencia:
                                            </h6>
                                            <div class="text-center mb-3">
    <p class="fw-bold mb-2">Escanea el QR para pagar con Yape</p>
    <img src="/img/yape.jpg" 
         alt="QR Yape" 
         class="img-fluid rounded shadow"
         style="max-width: 220px;">
</div>

<p class="mb-1"><strong>Nombre:</strong> Restaurant Sabor Peruano</p>
<p class="mb-0"><strong>Monto:</strong> S/. {{ number_format($total, 2) }}</p>

                                            <p class="mb-1"><strong>Nombre:</strong> Restaurant Sabor Peruano</p>
                                            <p class="mb-0"><strong>Monto:</strong> S/. {{ number_format($total, 2) }}</p>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Número de Operación *</label>
                                            <input type="text" name="numero_operacion" class="form-control form-control-lg @error('numero_operacion') is-invalid @enderror" 
                                                   placeholder="Ingresa el código de operación">
                                            @error('numero_operacion')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Información Efectivo -->
                                    <div id="infoEfectivo" style="display: none;">
                                        <div class="alert alert-warning">
                                            <i class="ri-information-line"></i> 
                                            <strong>Pago en Efectivo:</strong><br>
                                            El pago se realizará cuando llegue el delivery a tu domicilio. Asegúrate de tener el monto exacto.
                                        </div>
                                    </div>

                                    <input type="hidden" name="monto" value="{{ $total }}">

                                    <div class="d-grid gap-2 mt-4">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="ri-check-line"></i> Confirmar Pedido
                                        </button>
                                        <a href="{{ route('delivery.create') }}" class="btn btn-outline-secondary">
                                            <i class="ri-arrow-left-line"></i> Volver
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('metodoPago').addEventListener('change', function() {
        const metodo = this.value;
        const infoPagoDigital = document.getElementById('infoPagoDigital');
        const infoEfectivo = document.getElementById('infoEfectivo');
        
        infoPagoDigital.style.display = 'none';
        infoEfectivo.style.display = 'none';
        
        if (metodo === 'yape' || metodo === 'plin' || metodo === 'transferencia') {
            infoPagoDigital.style.display = 'block';
        } else if (metodo === 'efectivo') {
            infoEfectivo.style.display = 'block';
        }
    });
    </script>
</body>
</html>