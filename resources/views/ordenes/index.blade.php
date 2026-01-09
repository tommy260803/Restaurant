@extends('layouts.plantilla')

@section('title', 'Gestión de Órdenes - Mesas')

@section('contenido')
<div class="container-fluid py-4" style="background-color: #1a1a2e; min-height: 100vh;">
    
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-white mb-0">Gestión de Órdenes</h2>
            <p class="text-muted">Selecciona una mesa para tomar o ver órdenes</p>
        </div>
    </div>

    {{-- Mensajes de éxito/error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Navegación de pestañas --}}
    <ul class="nav nav-tabs mb-4" style="border-bottom: 2px solid #2d2d44;">
        <li class="nav-item">
            <a class="nav-link active" style="background-color: transparent; color: #fff; border-color: transparent; border-bottom: 2px solid #28a745;">
                <i class="bi bi-grid-3x3-gap me-2"></i>Mesas
            </a>
        </li>
    </ul>

    {{-- Grid de Mesas --}}
    <div class="row g-3">
        @forelse($mesas as $mesa)
            @php
                // Obtener estado calculado (considerando reservas)
                $estadoCalculado = $mesa->estado_calculado ?? $mesa->estado;

                //trae reservas de HOY
                $proximaReservaHoy = $mesa->proxima_reserva_hoy ?? null;
            @endphp
            
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                <div class="card h-100" style="background-color: #2d2d44; border: 1px solid #3a3a54; border-radius: 8px;">
                    <div class="card-body text-center">
                        {{-- Número de mesa --}}
                        <h5 class="card-title text-white mb-3">
                            Mesa #{{ $mesa->numero }}
                        </h5>

                        {{-- Información adicional --}}
                        <p class="small mb-3" style="color: #b8b8d1;">
                            <i class="bi bi-people me-1"></i>Capacidad: {{ $mesa->capacidad }}
                        </p>

                        {{-- Badge de estado CALCULADO --}}
                        @if($estadoCalculado === 'disponible')
                            <span class="badge bg-success mb-3">Libre</span>
                        @elseif($estadoCalculado === 'ocupada')
                            <span class="badge bg-warning text-dark mb-3">Ocupada</span>
                        @elseif($estadoCalculado === 'reservada')
                            <span class="badge bg-info text-dark mb-3">Reservada Ahora</span>
                        @elseif($estadoCalculado === 'mantenimiento')
                            <span class="badge bg-danger mb-3">Mantenimiento</span>
                        @else
                            <span class="badge bg-secondary mb-3">{{ ucfirst($estadoCalculado) }}</span>
                        @endif

                        {{-- Información de próxima reserva si existe --}}
                        @if($estadoCalculado === 'disponible' && $proximaReservaHoy)
                            <div class="alert alert-info py-1 px-2 mb-3" style="font-size: 0.75rem; background-color: #17a2b8; border-color: #17a2b8; color: #fff;">
                                <i class="bi bi-calendar-check me-1"></i>
                                <strong>Reserva:</strong><br>
                                {{ \Carbon\Carbon::parse($proximaReservaHoy->fecha_reserva)->format('d/m/Y') }}<br>
                                <strong>{{ \Carbon\Carbon::parse($proximaReservaHoy->hora_reserva)->format('g:i A') }}</strong>
                            </div>
                        @endif

                        {{-- Botón de acción según el estado CALCULADO --}}
                        @if($estadoCalculado === 'disponible')
                            {{-- Mesa disponible: Abrir mesa --}}
                            <form action="{{ route('ordenes.abrir', $mesa->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="btn w-100" 
                                        style="background-color: #28a745; color: #fff; font-weight: 500;">
                                    Abrir Mesa
                                </button>
                            </form>
                        @elseif($estadoCalculado === 'ocupada')
                            {{-- Mesa ocupada: Ver órdenes --}}
                            <a href="{{ route('ordenes.ver', $mesa->id) }}" 
                               class="btn w-100" 
                               style="background-color: #ffc107; color: #000; font-weight: 500;">
                                Ver Órdenes
                            </a>
                        @elseif($estadoCalculado === 'reservada')
                            {{-- Mesa reservada AHORA: No se puede usar --}}
                            <button class="btn w-100" 
                                    disabled
                                    style="background-color: #6c757d; color: #fff; font-weight: 500; cursor: not-allowed;"
                                    data-bs-toggle="tooltip"
                                    title="Mesa con reserva activa">
                                <i class="bi bi-calendar-x me-1"></i>
                                Reservada
                            </button>
                            
                            {{-- Mostrar info de la reserva activa --}}
                            @php
                                $ahora = \Carbon\Carbon::now();
                                $reservaActiva = $mesa->reservas->first(function($r) use ($ahora) {
                                    // Solo buscar en reservas de HOY que ya fueron cargadas
                                    $horaReserva = \Carbon\Carbon::parse($r->fecha_reserva->toDateString() . ' ' . $r->hora_reserva);
                                    return $ahora->between($horaReserva->copy()->subMinutes(60), $horaReserva->copy()->addHours(5));
                                });
                            @endphp
                            
                            @if($reservaActiva)
                                <small class="text-info d-block mt-2" style="font-size: 0.7rem;">
                                    <strong>{{ \Carbon\Carbon::parse($reservaActiva->hora_reserva)->format('g:i A') }}</strong><br>
                                    {{ $reservaActiva->nombre_cliente }}
                                </small>
                            @endif
                        @elseif($estadoCalculado === 'mantenimiento')
                            {{-- Mesa en mantenimiento: No disponible --}}
                            <button class="btn w-100" 
                                    disabled
                                    style="background-color: #6c757d; color: #fff; font-weight: 500; cursor: not-allowed;">
                                <i class="bi bi-tools me-1"></i>
                                En Mantenimiento
                            </button>
                        @else
                            {{-- Estado desconocido --}}
                            <button class="btn w-100" 
                                    disabled
                                    style="background-color: #6c757d; color: #fff; font-weight: 500;">
                                No Disponible
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info" style="background-color: #2d2d44; border-color: #3a3a54; color: #fff;">
                    <i class="bi bi-info-circle me-2"></i>No hay mesas registradas en el sistema.
                </div>
            </div>
        @endforelse
    </div>
    
</div>
@endsection

@push('styles')
<style>
    /* Dark Mode Styles */
    body {
        background-color: #1a1a2e !important;
        color: #fff;
    }

    .card:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .btn:hover:not(:disabled) {
        opacity: 0.9;
        transform: scale(1.02);
        transition: all 0.2s ease;
    }

    .nav-link:hover {
        border-bottom: 2px solid #28a745 !important;
    }

    /* Deshabilitar hover en botones disabled */
    .btn:disabled {
        opacity: 0.6;
    }

    /* Animación para badges */
    .badge {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    .alert-info {
        box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);
    }
</style>
@endpush

@push('scripts')
<script>
    // Inicializar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush