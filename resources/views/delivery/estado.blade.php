<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado del Pedido #{{ $pedido->id }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <style>
        :root {
            --peru-red: #b91c1c;
        }
        
        body {
            background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 2rem 0;
        }
        
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
            margin-bottom: 2rem;
        }
        
        .logo {
            height: 50px;
            width: 50px;
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
        
        .timeline {
            position: relative;
            padding: 2rem 0;
        }
        
        .timeline-item {
            position: relative;
            padding-left: 60px;
            padding-bottom: 40px;
        }
        
        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 23px;
            top: 50px;
            width: 3px;
            height: calc(100% - 30px);
            background: #e5e7eb;
        }
        
        .timeline-item.completed:not(:last-child)::before {
            background: #16a34a;
        }
        
        .timeline-marker {
            position: absolute;
            left: 0;
            top: 10px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #f3f4f6;
            border: 3px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #9ca3af;
        }
        
        .timeline-item.completed .timeline-marker {
            background: #16a34a;
            border-color: #16a34a;
            color: white;
        }
        
        .timeline-item.active .timeline-marker {
            background: var(--peru-red);
            border-color: var(--peru-red);
            color: white;
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.9;
            }
        }
        
        .timeline-content h6 {
            font-weight: 700;
            color: #6b7280;
            margin-bottom: 0.3rem;
        }
        
        .timeline-item.completed .timeline-content h6 {
            color: #16a34a;
        }
        
        .timeline-item.active .timeline-content h6 {
            color: var(--peru-red);
            font-size: 1.1rem;
        }
        
        .estado-badge {
            font-size: 1.3rem;
            padding: 0.7rem 2rem;
            border-radius: 50px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar-custom">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between w-100">
                <div class="d-flex align-items-center gap-3">
                    <img src="/img/resta.png" alt="Logo" class="logo">
                    <h4 class="mb-0 fw-bold" style="color: var(--peru-red);">Sabor Peruano</h4>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('delivery.consultar') }}" class="btn btn-outline-danger btn-sm">
                        <i class="ri-arrow-left-line"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="text-center mb-4">
                    <h1 class="fw-bold" style="color: var(--peru-red); font-size: 2.2rem;">
                        Estado de tu Pedido
                    </h1>
                    <p class="text-muted fs-5">Pedido #{{ $pedido->id }}</p>
                    
                    @php
                        $estadoConfig = [
                            'pendiente_pago' => ['color' => 'warning', 'icono' => 'time-line', 'texto' => 'Pendiente de Pago'],
                            'confirmado' => ['color' => 'info', 'icono' => 'check-line', 'texto' => 'Pago Confirmado'],
                            'en_preparacion' => ['color' => 'primary', 'icono' => 'fire-line', 'texto' => 'En Preparación'],
                            'listo' => ['color' => 'success', 'icono' => 'checkbox-circle-line', 'texto' => 'Listo para Enviar'],
                            'en_camino' => ['color' => 'info', 'icono' => 'e-bike-2-line', 'texto' => 'En Camino'],
                            'entregado' => ['color' => 'success', 'icono' => 'check-double-line', 'texto' => 'Entregado'],
                            'cancelado' => ['color' => 'danger', 'icono' => 'close-circle-line', 'texto' => 'Cancelado'],
                        ];
                        $estado = $estadoConfig[$pedido->estado] ?? $estadoConfig['pendiente_pago'];
                    @endphp
                    
                    <div class="mt-3">
                        <span class="badge bg-{{ $estado['color'] }} estado-badge">
                            <i class="ri-{{ $estado['icono'] }}"></i> {{ $estado['texto'] }}
                        </span>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4" style="color: var(--peru-red);">
                            <i class="ri-route-line"></i> Seguimiento del Pedido
                        </h5>
                        
                        <div class="timeline">
                            @php
                                $pasos = [
                                    'pendiente_pago' => ['titulo' => 'Pago Pendiente', 'icono' => 'ri-time-line'],
                                    'confirmado' => ['titulo' => 'Pago Confirmado', 'icono' => 'ri-check-line'],
                                    'en_preparacion' => ['titulo' => 'Preparando', 'icono' => 'ri-fire-line'],
                                    'listo' => ['titulo' => 'Listo', 'icono' => 'ri-checkbox-circle-line'],
                                    'en_camino' => ['titulo' => 'En Camino', 'icono' => 'ri-e-bike-2-line'],
                                    'entregado' => ['titulo' => 'Entregado', 'icono' => 'ri-check-double-line']
                                ];
                                
                                $estadosOrdenados = array_keys($pasos);
                                $estadoActualIndex = array_search($pedido->estado, $estadosOrdenados);
                            @endphp
                            
                            @foreach($pasos as $key => $paso)
                                @php
                                    $index = array_search($key, $estadosOrdenados);
                                    $completado = $index <= $estadoActualIndex;
                                    $activo = $key === $pedido->estado;
                                @endphp
                                
                                <div class="timeline-item {{ $completado ? 'completed' : '' }} {{ $activo ? 'active' : '' }}">
                                    <div class="timeline-marker">
                                        <i class="{{ $paso['icono'] }}"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>{{ $paso['titulo'] }}</h6>
                                        @if($activo)
                                            <small class="text-muted">Estado actual</small>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Info Pedido -->
                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-header text-white" style="background: var(--peru-red);">
                                <h5 class="mb-0"><i class="ri-information-line"></i> Información del Pedido</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <strong style="color: var(--peru-red);">Cliente:</strong><br>
                                    {{ $pedido->nombre_cliente }}
                                </div>
                                <div class="mb-3">
                                    <strong style="color: var(--peru-red);">Teléfono:</strong><br>
                                    {{ $pedido->telefono }}
                                </div>
                                <div class="mb-3">
                                    <strong style="color: var(--peru-red);">Email:</strong><br>
                                    {{ $pedido->email }}
                                </div>
                                <div class="mb-3">
                                    <strong style="color: var(--peru-red);">Dirección:</strong><br>
                                    {{ $pedido->direccion_entrega }}
                                    @if($pedido->referencia)
                                        <br><small class="text-muted">Ref: {{ $pedido->referencia }}</small>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <strong style="color: var(--peru-red);">Fecha/Hora:</strong><br>
                                    {{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y') }} 
                                    - {{ \Carbon\Carbon::parse($pedido->hora_pedido)->format('H:i') }}
                                </div>
                                @if($pedido->comentarios)
                                    <div class="mb-3">
                                        <strong style="color: var(--peru-red);">Comentarios:</strong><br>
                                        {{ $pedido->comentarios }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Detalle Platos -->
                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-header text-white" style="background: #16a34a;">
                                <h5 class="mb-0"><i class="ri-restaurant-line"></i> Detalle del Pedido</h5>
                            </div>
                            <div class="card-body p-4">
                                @php $total = 0; @endphp
                                @foreach($pedido->platos as $item)
                                    @php 
                                        $subtotal = $item->precio * $item->cantidad;
                                        $total += $subtotal;
                                        
                                        $estadoPlatoConfig = [
                                            'pendiente' => ['color' => 'secondary', 'icono' => 'time-line'],
                                            'en_preparacion' => ['color' => 'warning', 'icono' => 'fire-line'],
                                            'preparado' => ['color' => 'success', 'icono' => 'check-line'],
                                        ];
                                        $estadoPlato = $estadoPlatoConfig[$item->estado] ?? $estadoPlatoConfig['pendiente'];
                                    @endphp
                                    
                                    <div class="border-bottom pb-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-bold">{{ $item->plato->nombre }}</h6>
                                                <small class="text-muted">
                                                    Cantidad: {{ $item->cantidad }} x S/. {{ number_format($item->precio, 2) }}
                                                </small>
                                                @if($item->notas)
                                                    <br><small class="text-info"><i class="ri-chat-3-line"></i> {{ $item->notas }}</small>
                                                @endif
                                                <br>
                                                <span class="badge bg-{{ $estadoPlato['color'] }} mt-2">
                                                    <i class="ri-{{ $estadoPlato['icono'] }}"></i> 
                                                    {{ ucfirst(str_replace('_', ' ', $item->estado)) }}
                                                </span>
                                            </div>
                                            <div class="text-end">
                                                <strong>S/. {{ number_format($subtotal, 2) }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                    <h5 class="mb-0 fw-bold">TOTAL:</h5>
                                    <h3 class="mb-0 fw-bold" style="color: #16a34a;">S/. {{ number_format($total, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estado Pago -->
                @if($pedido->pago)
                    <div class="card mt-4">
                        <div class="card-header text-white" style="background: #0891b2;">
                            <h5 class="mb-0"><i class="ri-bank-card-line"></i> Estado del Pago</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Método:</strong><br>
                                    <span class="text-uppercase badge bg-secondary">{{ $pedido->pago->metodo }}</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Monto:</strong><br>
                                    <span class="fs-5 fw-bold" style="color: #16a34a;">S/. {{ number_format($pedido->pago->monto, 2) }}</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Estado:</strong><br>
                                    @php
                                        $estadoPagoConfig = [
                                            'pendiente' => ['color' => 'warning', 'texto' => 'Pendiente'],
                                            'confirmado' => ['color' => 'success', 'texto' => 'Confirmado'],
                                            'rechazado' => ['color' => 'danger', 'texto' => 'Rechazado'],
                                        ];
                                        $estadoPago = $estadoPagoConfig[$pedido->pago->estado] ?? $estadoPagoConfig['pendiente'];
                                    @endphp
                                    <span class="badge bg-{{ $estadoPago['color'] }} fs-6">{{ $estadoPago['texto'] }}</span>
                                </div>
                            </div>
                            @if($pedido->pago->numero_operacion)
                                <div class="mt-3">
                                    <strong>N° Operación:</strong> {{ $pedido->pago->numero_operacion }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Botones -->
                <div class="text-center mt-4">
                    <a href="{{ route('delivery.consultar') }}" class="btn btn-outline-danger btn-lg me-2">
                        <i class="ri-arrow-left-line"></i> Volver
                    </a>
                    <a href="{{ route('delivery.create') }}" class="btn btn-lg" style="background: #16a34a; color: white;">
                        <i class="ri-add-circle-line"></i> Hacer Nuevo Pedido
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>