<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hacer Pedido - Delivery</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <style>
        :root {
            --peru-red: #b91c1c;
            --peru-gold: #d4af37;
        }
        
        body {
            background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            padding: 2rem 0;
        }
        
        .header-section {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .header-section h1 {
            color: var(--peru-red);
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .header-section .subtitle {
            color: #6b7280;
            font-size: 1.1rem;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            border-radius: 15px 15px 0 0 !important;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }
        
        .btn-primary {
            background: var(--peru-red);
            border: none;
            border-radius: 10px;
            padding: 0.7rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 8px 22px rgba(185,28,28,0.18);
        }
        
        .btn-primary:hover {
            background: #991b1b;
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(185,28,28,0.25);
        }
        
        .btn-success {
            background: #16a34a;
            border: none;
            border-radius: 10px;
            padding: 0.7rem 1.5rem;
            font-weight: 600;
        }
        
        .btn-success:hover {
            background: #15803d;
        }
        
        .plato-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .plato-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        
        .plato-item {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        
        .logo {
            height: 50px;
            width: 50px;
            object-fit: contain;
        }
        
        .form-control:focus {
            border-color: var(--peru-red);
            box-shadow: 0 0 0 0.2rem rgba(185,28,28,0.15);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar-custom mb-4">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between w-100">
                <div class="d-flex align-items-center gap-3">
                    <img src="/img/resta.png" alt="Logo" class="logo">
                    <h4 class="mb-0 fw-bold" style="color: var(--peru-red);">Sabor Peruano</h4>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('delivery.consultar') }}" class="btn btn-outline-danger btn-sm">
                        <i class="ri-search-eye-line"></i> Consultar Pedido
                    </a>
                    <a href="{{ route('presentacion') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ri-home-line"></i> Inicio
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container main-container">
        <!-- Header -->
        <div class="header-section">
            <h1><i class="ri-e-bike-2-line"></i> Hacer Pedido Delivery</h1>
            <p class="subtitle">Selecciona tus platos favoritos y te los llevamos a tu puerta</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="ri-error-warning-line"></i> Errores en el formulario:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('delivery.store') }}" method="POST" id="formDelivery">
            @csrf
            
            <div class="row">
                <!-- Columna Izquierda: Datos del Cliente -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header text-white" style="background: var(--peru-red);">
                            <h5 class="mb-0"><i class="ri-user-location-line"></i> Datos de Entrega</h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Nombre -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nombre Completo *</label>
                                <input type="text" name="nombre_cliente" class="form-control @error('nombre_cliente') is-invalid @enderror" 
                                       value="{{ old('nombre_cliente') }}" required>
                                @error('nombre_cliente')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email *</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Teléfono -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Teléfono *</label>
                                <input type="tel" name="telefono" class="form-control @error('telefono') is-invalid @enderror" 
                                       value="{{ old('telefono') }}" placeholder="999 999 999" required>
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Dirección -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Dirección de Entrega *</label>
                                <textarea name="direccion_entrega" class="form-control @error('direccion_entrega') is-invalid @enderror" 
                                          rows="2" required>{{ old('direccion_entrega') }}</textarea>
                                @error('direccion_entrega')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Referencia -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Referencia</label>
                                <input type="text" name="referencia" class="form-control" 
                                       value="{{ old('referencia') }}" placeholder="Ej: Casa azul, portón negro">
                            </div>

                            <!-- Fecha y Hora -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Fecha *</label>
                                    <input type="date" name="fecha_pedido" class="form-control @error('fecha_pedido') is-invalid @enderror" 
                                           value="{{ old('fecha_pedido', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                                    @error('fecha_pedido')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Hora *</label>
                                    <input type="time" name="hora_pedido" class="form-control @error('hora_pedido') is-invalid @enderror" 
                                           value="{{ old('hora_pedido', date('H:i')) }}" required>
                                    @error('hora_pedido')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Comentarios -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Comentarios Adicionales</label>
                                <textarea name="comentarios" class="form-control" rows="2" 
                                          placeholder="Ej: Sin cebolla, extra picante...">{{ old('comentarios') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Selección de Platos -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header text-white" style="background: #16a34a;">
                            <h5 class="mb-0"><i class="ri-shopping-cart-line"></i> Tu Pedido</h5>
                        </div>
                        <div class="card-body p-3" style="max-height: 450px; overflow-y: auto;">
                            <div id="listaPlatosSeleccionados">
                                <p class="text-muted text-center py-5">
                                    <i class="ri-shopping-cart-line" style="font-size: 3rem; display: block; margin-bottom: 1rem;"></i>
                                    No has agregado platos aún
                                </p>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">Total:</h5>
                                <h4 class="mb-0 fw-bold" style="color: #16a34a;">S/. <span id="totalPedido">0.00</span></h4>
                            </div>
                        </div>
                    </div>

                    <!-- Botón para agregar platos -->
                    <button type="button" class="btn btn-outline-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#modalPlatos">
                        <i class="ri-add-circle-line"></i> Agregar Platos
                    </button>

                    <!-- Botón enviar -->
                    <button type="submit" class="btn btn-success w-100 btn-lg" id="btnEnviar" disabled>
                        <i class="ri-check-line"></i> Continuar al Pago
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal para seleccionar platos -->
    <div class="modal fade" id="modalPlatos" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header text-white" style="background: var(--peru-red);">
                    <h5 class="modal-title"><i class="ri-restaurant-line"></i> Menú Disponible</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        @foreach($platos as $plato)
                        <div class="col-md-6">
                            <div class="card h-100 plato-card">
                                @if($plato->imagen)
                                    <img src="{{ asset('storage/' . $plato->imagen) }}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="{{ $plato->nombre }}">
                                @else
                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 150px;">
                                        <i class="ri-restaurant-2-line" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h6 class="card-title fw-bold">{{ $plato->nombre }}</h6>
                                    <p class="card-text text-muted small">{{ Str::limit($plato->descripcion, 60) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge" style="background: #16a34a; font-size: 1rem;">S/. {{ number_format($plato->precio, 2) }}</span>
                                        <button type="button" class="btn btn-sm btn-primary btnAgregarPlato" 
                                                data-id="{{ $plato->idPlatoProducto }}" 
                                                data-nombre="{{ $plato->nombre }}" 
                                                data-precio="{{ $plato->precio }}">
                                            <i class="ri-add-line"></i> Agregar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    let platosSeleccionados = [];
    let contadorPlatos = 0;

    // Agregar plato
    document.querySelectorAll('.btnAgregarPlato').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = parseInt(this.dataset.id);
            const nombre = this.dataset.nombre;
            const precio = parseFloat(this.dataset.precio);
            
            contadorPlatos++;
            
            const plato = {
                index: contadorPlatos,
                id: id,
                nombre: nombre,
                precio: precio,
                cantidad: 1,
                notas: ''
            };
            
            platosSeleccionados.push(plato);
            actualizarListaPlatos();
        });
    });

    function actualizarListaPlatos() {
    const lista = document.getElementById('listaPlatosSeleccionados');
    
    if (platosSeleccionados.length === 0) {
        lista.innerHTML = `
            <p class="text-muted text-center py-5">
                <i class="ri-shopping-cart-line" style="font-size: 3rem; display: block; margin-bottom: 1rem;"></i>
                No has agregado platos aún
            </p>
        `;
        document.getElementById('btnEnviar').disabled = true;
        return;
    }
    
    document.getElementById('btnEnviar').disabled = false;
    
    let html = '';
    let total = 0;
    
    platosSeleccionados.forEach((plato, idx) => {
        const subtotal = plato.precio * plato.cantidad;
        total += subtotal;
        
        html += `
            <div class="card mb-2 plato-item">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h6 class="mb-1 fw-bold">${plato.nombre}</h6>
                            <small class="text-muted">S/. ${plato.precio.toFixed(2)} c/u</small>
                        </div>
                        <div class="col-4">
                            <div class="input-group input-group-sm">
                                <button type="button" class="btn btn-outline-secondary btnMenos" data-index="${idx}">-</button>
                                <input type="number" class="form-control text-center cantidad-input" value="${plato.cantidad}" min="1" data-index="${idx}" readonly>
                                <button type="button" class="btn btn-outline-secondary btnMas" data-index="${idx}">+</button>
                            </div>
                        </div>
                        <div class="col-2 text-end">
                            <button type="button" class="btn btn-sm btn-danger btnEliminar" data-index="${idx}">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mt-2">
                        <input type="text" class="form-control form-control-sm nota-input" 
                               placeholder="Notas (opcional)" 
                               value="${plato.notas || ''}" 
                               data-index="${idx}">
                    </div>
                </div>
            </div>
        `;
    });
    
    lista.innerHTML = html;
    document.getElementById('totalPedido').textContent = total.toFixed(2);
    
    // IMPORTANTE: Agregar los hidden inputs FUERA de la lista visual
    actualizarHiddenInputs();
    agregarEventListeners();
}

// Nueva función para manejar los hidden inputs
function actualizarHiddenInputs() {
    // Buscar o crear contenedor para los inputs hidden
    let container = document.getElementById('hidden-inputs-container');
    
    if (!container) {
        container = document.createElement('div');
        container.id = 'hidden-inputs-container';
        container.style.display = 'none';
        document.getElementById('formDelivery').appendChild(container);
    }
    
    // Limpiar contenedor
    container.innerHTML = '';
    
    // Agregar inputs hidden para cada plato
    platosSeleccionados.forEach((plato, idx) => {
        container.innerHTML += `
            <input type="hidden" name="platos[${idx}][id]" value="${plato.id ?? ''}">
            <input type="hidden" name="platos[${idx}][cantidad]" value="${plato.cantidad}" class="cantidad-hidden-${idx}">
            <input type="hidden" name="platos[${idx}][notas]" value="${plato.notas || ''}" class="notas-hidden-${idx}">
        `;
    });
}

    function agregarEventListeners() {
    document.querySelectorAll('.btnMas').forEach(btn => {
        btn.addEventListener('click', function() {
            const index = this.dataset.index;
            platosSeleccionados[index].cantidad++;
            actualizarListaPlatos();
        });
    });
    
    document.querySelectorAll('.btnMenos').forEach(btn => {
        btn.addEventListener('click', function() {
            const index = this.dataset.index;
            if (platosSeleccionados[index].cantidad > 1) {
                platosSeleccionados[index].cantidad--;
                actualizarListaPlatos();
            }
        });
    });
    
    document.querySelectorAll('.btnEliminar').forEach(btn => {
        btn.addEventListener('click', function() {
            const index = this.dataset.index;
            platosSeleccionados.splice(index, 1);
            actualizarListaPlatos();
        });
    });
    
    document.querySelectorAll('.nota-input').forEach(input => {
        input.addEventListener('input', function() {
            const index = this.dataset.index;
            platosSeleccionados[index].notas = this.value;
            // Actualizar el hidden input correspondiente
            const hiddenInput = document.querySelector(`.notas-hidden-${index}`);
            if (hiddenInput) {
                hiddenInput.value = this.value;
            }
        });
    });
}
    </script>
    <script>
        document.getElementById('formDelivery').addEventListener('submit', function () {
        actualizarHiddenInputs();
        });
    </script>

</body>
</html>