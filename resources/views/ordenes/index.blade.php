{{-- ordenes/index.blade.php--}}
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
            <a class="nav-link active"
                style="background-color: transparent; color: #fff; border-color: transparent; border-bottom: 2px solid #28a745;">
                <i class="bi bi-grid-3x3-gap me-2"></i>Mesas
            </a>
        </li>
    </ul>

    {{-- Grid de Mesas --}}
    <div class="row g-3">
        @forelse($mesas as $mesa)
        @php
        $estadoCalculado = $mesa->estado_calculado ?? $mesa->estado;
        $proximaReservaHoy = $mesa->proxima_reserva_hoy ?? null;
        $esReserva = $mesa->es_reserva ?? false;
        @endphp

        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
            <div class="card h-100" style="background-color: #2d2d44; border: 1px solid #3a3a54; border-radius: 8px;">
                <div class="card-body text-center position-relative">
                    
                    {{-- ✅ DISTINTIVO PEQUEÑO: Esquina superior derecha --}}
                    @if($estadoCalculado === 'ocupada')
                        @if($esReserva)
                            {{-- Distintivo RESERVA: Gradiente sutil --}}
                            <span class="badge position-absolute top-0 end-0 m-2" 
                                  style="background: linear-gradient(135deg, #ffc107 0%, #17a2b8 100%); 
                                         color: #000; 
                                         font-size: 0.65rem; 
                                         padding: 0.25rem 0.5rem;
                                         box-shadow: 0 2px 4px rgba(0,0,0,0.2);"
                                  title="Mesa ocupada por reserva">
                                <i class="bi bi-calendar-check"></i>
                            </span>
                        @else
                            {{-- Distintivo ORDEN DIRECTA: Amarillo simple --}}
                            <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2" 
                                  style="font-size: 0.65rem; 
                                         padding: 0.25rem 0.5rem;
                                         box-shadow: 0 2px 4px rgba(0,0,0,0.2);"
                                  title="Mesa ocupada por orden directa">
                                <i class="bi bi-receipt"></i>
                            </span>
                        @endif
                    @endif
                    
                    {{-- Número de mesa --}}
                    <h5 class="card-title text-white mb-3">
                        Mesa #{{ $mesa->numero }}
                    </h5>

                    {{-- Información adicional --}}
                    <p class="small mb-3" style="color: #b8b8d1;">
                        <i class="bi bi-people me-1"></i>Capacidad: {{ $mesa->capacidad }}
                    </p>

                    {{-- Badge de estado principal --}}
                    @if($estadoCalculado === 'disponible')
                        <span class="badge bg-success mb-3">Libre</span>
                        
                    @elseif($estadoCalculado === 'ocupada')
                        <span class="badge bg-warning text-dark mb-3">Ocupada</span>
                        
                    @elseif($estadoCalculado === 'reservada')
                        <span class="badge bg-info text-dark mb-3">Reservada</span>
                        
                    @elseif($estadoCalculado === 'mantenimiento')
                        <span class="badge bg-danger mb-3">Mantenimiento</span>
                    @else
                        <span class="badge bg-secondary mb-3">{{ ucfirst($estadoCalculado) }}</span>
                    @endif

                    {{-- Información de próxima reserva --}}
                    @if($estadoCalculado === 'disponible' && $proximaReservaHoy)
                    <div class="alert alert-info py-1 px-2 mb-3"
                        style="font-size: 0.7rem; background-color: rgba(23, 162, 184, 0.15); border: 1px solid #17a2b8; color: #17a2b8;">
                        <i class="bi bi-calendar-check me-1"></i>
                        <strong>Reserva:</strong><br>
                        {{ \Carbon\Carbon::parse($proximaReservaHoy->hora_reserva)->format('g:i A') }}
                    </div>
                    @endif

                    {{-- Info adicional para mesas ocupadas por reserva --}}
                    @if($estadoCalculado === 'ocupada' && $esReserva)
                    @php
                    $ahora = \Carbon\Carbon::now();
                    $reservaActual = $mesa->reservas->first(function($r) use ($ahora) {
                        $horaReserva = \Carbon\Carbon::parse($r->fecha_reserva->toDateString() . ' ' . $r->hora_reserva);
                        return $ahora->between($horaReserva->copy(), $horaReserva->copy()->addHours(3));
                    });
                    @endphp

                    @if($reservaActual)
                    <div class="alert alert-info py-1 px-2 mb-3"
                        style="font-size: 0.7rem; background-color: rgba(23, 162, 184, 0.1); border: 1px solid #17a2b8; color: #17a2b8;">
                        <i class="bi bi-person-check me-1"></i>
                        <strong>{{ $reservaActual->nombre_cliente }}</strong>
                        @if($reservaActual->platos->isNotEmpty())
                        <br><small class="badge badge-sm mt-1"
                            style="background-color: #28a745; color: #fff; font-size: 0.6rem;">
                            <i class="bi bi-basket"></i> {{ $reservaActual->platos->count() }}
                        </small>
                        @endif
                    </div>
                    @endif
                    @endif

                    {{-- Botón de acción --}}
                    @if($estadoCalculado === 'disponible')
                        <form action="{{ route('ordenes.abrir', $mesa->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn w-100"
                                style="background-color: #28a745; color: #fff; font-weight: 500;">
                                Abrir Mesa
                            </button>
                        </form>
                        
                    @elseif($estadoCalculado === 'ocupada')
                        <a href="{{ route('ordenes.ver', $mesa->id) }}" class="btn w-100"
                            style="background-color: #ffc107; color: #000; font-weight: 500;">
                            Ver Órdenes
                        </a>
                        
                    @elseif($estadoCalculado === 'reservada')
                        <button class="btn w-100" disabled
                            style="background-color: #6c757d; color: #fff; font-weight: 500; cursor: not-allowed;">
                            <i class="bi bi-lock me-1"></i>Reservada
                        </button>
                        
                    @elseif($estadoCalculado === 'mantenimiento')
                        <button class="btn w-100" disabled
                            style="background-color: #6c757d; color: #fff; font-weight: 500; cursor: not-allowed;">
                            <i class="bi bi-tools me-1"></i>Mantenimiento
                        </button>
                    @else
                        <button class="btn w-100" disabled
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

    {{-- Leyenda simplificada --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card" style="background-color: #2d2d44; border: 1px solid #3a3a54;">
                <div class="card-body">
                    <h6 class="text-white mb-3">
                        <i class="bi bi-info-circle me-2"></i>Leyenda:
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2">Libre</span>
                                <small style="color: #b8b8d1;">Mesa disponible</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-warning text-dark me-2">
                                    <i class="bi bi-receipt"></i>
                                </span>
                                <small style="color: #b8b8d1;">Orden directa</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <span class="badge me-2" style="background: linear-gradient(135deg, #ffc107 0%, #17a2b8 100%); color: #000;">
                                    <i class="bi bi-calendar-check"></i>
                                </span>
                                <small style="color: #b8b8d1;">Reserva activa</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
body {
    background-color: #1a1a2e !important;
    color: #fff;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.btn:hover:not(:disabled) {
    opacity: 0.9;
    transform: scale(1.02);
    transition: all 0.2s ease;
}

.badge {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Distintivo con sombra sutil */
.position-absolute.badge {
    z-index: 10;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush