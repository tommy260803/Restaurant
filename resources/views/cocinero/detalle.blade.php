{{-- cocinero/detalle.blade.php --}}
@extends('layouts.plantilla')

@section('title', 'Cocina - Detalle de Pedido')

@section('contenido')
<div class="container-fluid mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4">
      <i class="bi bi-eye me-2"></i> Detalle de Pedido #{{ $pedido->id }}
    </h1>
    
    @if($pedido->tipo === 'orden')
      <span class="badge bg-secondary fs-6">
        <i class="bi bi-cart-check me-1"></i>Pedido Directo
      </span>
    @else
      <span class="badge bg-info fs-6">
        <i class="bi bi-calendar-check me-1"></i>Pedido por Reserva
      </span>
    @endif

    <a href="{{ route('cocinero.pedidos') }}" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
  </div>
  
  <div class="row g-3">
    <!-- Información principal del pedido -->
    <div class="col-12 col-lg-8">
      <div class="card shadow-sm">
        <div class="card-header bg-primary bg-gradient text-white">
          <h5 class="mb-0">
            <i class="bi bi-info-circle me-2"></i>Información del Pedido
          </h5>
        </div>
        <div class="card-body">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <div class="mb-2">
                <strong><i class="bi bi-table me-1"></i>Mesa:</strong>
                @if($pedido->mesa_numero !== '—')
                  <span class="badge bg-dark">Mesa {{ $pedido->mesa_numero }}</span>
                @else
                  <span class="text-muted">{{ $pedido->mesa_numero }}</span>
                @endif
              </div>
              
              <div class="mb-2">
                <strong><i class="bi bi-person me-1"></i>Cliente:</strong>
                {{ $pedido->cliente_nombre }}
              </div>
              
              @if($pedido->tipo === 'reserva' && $pedido->personas !== '—')
                <div class="mb-2">
                  <strong><i class="bi bi-people me-1"></i>Personas:</strong>
                  {{ $pedido->personas }}
                </div>
              @endif
            </div>

            <div class="col-md-6">
              <div class="mb-2">
                <strong><i class="bi bi-clock me-1"></i>Hora de {{ $pedido->tipo === 'orden' ? 'apertura' : 'ingreso' }}:</strong>
                {{ $pedido->hora_ingreso }}
              </div>

              <div class="mb-2">
                <strong><i class="bi bi-flag me-1"></i>Estado:</strong>
                @php
                  $badgeClass = match($pedido->estado) {
                    'Enviado a cocina' => 'bg-warning',
                    'En preparación' => 'bg-info',
                    'Preparado' => 'bg-success',
                    default => 'bg-secondary'
                  };
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $pedido->estado }}</span>
              </div>
            </div>
          </div>

          <hr>

          <!-- Detalle del plato -->
          <div class="card bg-light mb-3">
            <div class="card-body">
              <h6 class="card-title text-primary">
                <i class="bi bi-dish me-2"></i>Detalle del Plato
              </h6>
              <div class="row g-3">
                <div class="col-md-8">
                  <div class="mb-2">
                    <strong>Plato:</strong> {{ $pedido->plato_nombre }}
                  </div>
                  @if($pedido->plato_descripcion)
                    <div class="text-muted small">
                      <em>{{ $pedido->plato_descripcion }}</em>
                    </div>
                  @endif
                </div>
                <div class="col-md-4 text-end">
                  <div class="mb-2">
                    <strong>Cantidad:</strong>
                    <span class="badge bg-info fs-6">{{ $pedido->cantidad }}</span>
                  </div>
                  <div>
                    <strong>Precio:</strong>
                    <span class="text-success fw-bold fs-5">S/ {{ number_format($pedido->precio, 2) }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Notas/Observaciones -->
          <div>
            <h6><i class="bi bi-chat-left-text me-2"></i>Observaciones</h6>
            <div class="border rounded p-3 bg-white">
              @if(filled($pedido->notas))
                {{ $pedido->notas }}
              @else
                <em class="text-muted">Sin observaciones especiales</em>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Panel de acciones -->
    <div class="col-12 col-lg-4">
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-light">
          <strong><i class="bi bi-gear me-2"></i>Acciones Rápidas</strong>
        </div>
        <div class="card-body d-grid gap-2">
          @if($pedido->estado === 'Enviado a cocina')
            <form action="{{ route('cocinero.pedidos.preparar', $pedido->id) }}" method="POST">
              @csrf
              <input type="hidden" name="tipo" value="{{ $pedido->tipo }}">
              <button class="btn btn-primary w-100">
                <i class="bi bi-fire me-1"></i> Marcar En Preparación
              </button>
            </form>
          @elseif($pedido->estado === 'En preparación')
            <form action="{{ route('cocinero.pedidos.finalizar', $pedido->id) }}" method="POST">
              @csrf
              <input type="hidden" name="tipo" value="{{ $pedido->tipo }}">
              <button class="btn btn-success w-100">
                <i class="bi bi-check2 me-1"></i> Marcar Como Preparado
              </button>
            </form>
          @else
            <div class="alert alert-success mb-0">
              <i class="bi bi-check-circle me-2"></i>
              Este pedido ya está <strong>{{ $pedido->estado }}</strong>
            </div>
          @endif
        </div>
      </div>

      <!-- Panel de incidencias -->
      <div class="card shadow-sm">
        <div class="card-header bg-light">
          <strong><i class="bi bi-flag me-2"></i>Reportar Incidencia</strong>
        </div>
        <div class="card-body">
          <form action="{{ route('cocinero.pedidos.incidencia', $pedido->id) }}" method="POST" class="d-grid gap-2">
            @csrf
            <input type="hidden" name="tipo" value="{{ $pedido->tipo }}">
            <textarea 
              name="notas" 
              rows="4" 
              class="form-control" 
              placeholder="Describe la incidencia (ej: faltan insumos, plato quemado, etc.)"
            >{{ old('notas', $pedido->notas ?: '') }}</textarea>
            <button class="btn btn-outline-danger">
              <i class="bi bi-exclamation-triangle me-1"></i> Registrar Incidencia
            </button>
          </form>
          <small class="text-muted mt-2 d-block">
            <i class="bi bi-info-circle me-1"></i>
            Esto agregará o actualizará las observaciones del pedido
          </small>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  .card {
    transition: transform 0.2s ease;
  }
  
  .card:hover {
    transform: translateY(-2px);
  }
  
  .badge {
    font-weight: 500;
  }
</style>
@endpush