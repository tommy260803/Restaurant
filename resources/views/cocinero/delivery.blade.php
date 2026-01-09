@extends('layouts.plantilla')

@section('title', 'Cocina - Pedidos Delivery')

@section('contenido')
<div class="container-fluid mt-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4"><i class="bi bi-bicycle"></i> Pedidos Delivery - Cocina</h1>
    <div>
      <a href="{{ route('cocina.dashboard') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Dashboard
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
      <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- Estadísticas rápidas -->
  <div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
      <div class="card shadow-sm border-start border-warning border-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted mb-1 small">Pendientes</p>
              <h3 class="mb-0 fw-bold text-warning">
                {{ $pedidos->where('estado', 'confirmado')->sum(function($p) { return $p->platos->where('estado', 'pendiente')->count(); }) }}
              </h3>
            </div>
            <i class="bi bi-hourglass-split text-warning" style="font-size: 2rem;"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card shadow-sm border-start border-info border-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted mb-1 small">En Preparación</p>
              <h3 class="mb-0 fw-bold text-info">
                {{ $pedidos->sum(function($p) { return $p->platos->where('estado', 'en_preparacion')->count(); }) }}
              </h3>
            </div>
            <i class="bi bi-fire text-info" style="font-size: 2rem;"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card shadow-sm border-start border-success border-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted mb-1 small">Preparados</p>
              <h3 class="mb-0 fw-bold text-success">
                {{ $pedidos->sum(function($p) { return $p->platos->where('estado', 'preparado')->count(); }) }}
              </h3>
            </div>
            <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Lista de pedidos agrupados -->
  @forelse($pedidos as $pedido)
    <div class="card shadow-sm mb-3">
      <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(90deg, #0d6efd 0%, #0b5ed7 100%); color: white;">
        <div>
          <strong><i class="bi bi-receipt"></i> Pedido #{{ $pedido->id }}</strong>
          <span class="ms-2">|</span>
          <span class="ms-2"><i class="bi bi-person"></i> {{ $pedido->nombre_cliente }}</span>
          <span class="ms-2">|</span>
          <span class="ms-2"><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($pedido->hora_pedido)->format('H:i') }}</span>
        </div>
        <div>
          @php
            $estadoPedidoConfig = [
              'confirmado' => ['color' => 'info', 'texto' => 'Confirmado'],
              'en_preparacion' => ['color' => 'warning', 'texto' => 'En Preparación'],
              'listo' => ['color' => 'success', 'texto' => 'Listo'],
            ];
            $estadoPedido = $estadoPedidoConfig[$pedido->estado] ?? ['color' => 'secondary', 'texto' => $pedido->estado];
          @endphp
          <span class="badge bg-{{ $estadoPedido['color'] }}">{{ $estadoPedido['texto'] }}</span>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width: 35%;">Plato</th>
                <th class="text-center" style="width: 10%;">Cantidad</th>
                <th style="width: 25%;">Notas</th>
                <th class="text-center" style="width: 15%;">Estado</th>
                <th class="text-end" style="width: 15%;">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($pedido->platos as $item)
                <tr>
                  <td>
                    <i class="bi bi-dish text-primary me-2"></i>
                    <strong>{{ $item->plato->nombre }}</strong>
                    @if($item->plato->descripcion)
                      <br><small class="text-muted">{{ Str::limit($item->plato->descripcion, 40) }}</small>
                    @endif
                  </td>
                  <td class="text-center">
                    <span class="badge bg-info fs-6">{{ $item->cantidad }}</span>
                  </td>
                  <td>
                    <small class="text-muted fst-italic">{{ $item->notas ?? 'Sin notas' }}</small>
                  </td>
                  <td class="text-center">
                    @php
                      $estadoPlatoConfig = [
                        'pendiente' => ['color' => 'secondary', 'icono' => 'clock', 'texto' => 'Pendiente'],
                        'en_preparacion' => ['color' => 'warning', 'icono' => 'fire', 'texto' => 'Preparando'],
                        'preparado' => ['color' => 'success', 'icono' => 'check-circle', 'texto' => 'Preparado'],
                      ];
                      $estadoPlato = $estadoPlatoConfig[$item->estado] ?? $estadoPlatoConfig['pendiente'];
                    @endphp
                    <span class="badge bg-{{ $estadoPlato['color'] }}">
                      <i class="bi bi-{{ $estadoPlato['icono'] }}"></i> {{ $estadoPlato['texto'] }}
                    </span>
                  </td>
                  <td class="text-end">
                    <div class="btn-group btn-group-sm">
                      @if($item->estado === 'pendiente')
                        <form action="{{ route('cocina.delivery.cambiar-estado-plato', $item->id) }}" method="POST" class="d-inline">
                          @csrf
                          <input type="hidden" name="estado" value="en_preparacion">
                          <button class="btn btn-outline-primary" title="Iniciar preparación">
                            <i class="bi bi-fire"></i> Preparar
                          </button>
                        </form>
                      @endif
                      
                      @if($item->estado === 'en_preparacion')
                        <form action="{{ route('cocina.delivery.cambiar-estado-plato', $item->id) }}" method="POST" class="d-inline">
                          @csrf
                          <input type="hidden" name="estado" value="preparado">
                          <button class="btn btn-outline-success" title="Marcar como preparado">
                            <i class="bi bi-check2"></i> Listo
                          </button>
                        </form>
                      @endif

                      @if($item->estado === 'preparado')
                        <span class="text-success">
                          <i class="bi bi-check-circle-fill"></i> Completado
                        </span>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer bg-light">
        <div class="row">
          <div class="col-md-6">
            <small class="text-muted">
              <i class="bi bi-geo-alt"></i> {{ Str::limit($pedido->direccion_entrega, 50) }}
            </small>
          </div>
          <div class="col-md-6 text-end">
            @php
              $todosPlatosPreparados = $pedido->platos->every(function($item) {
                return $item->estado === 'preparado';
              });
            @endphp
            @if($todosPlatosPreparados)
              <span class="badge bg-success">
                <i class="bi bi-check-all"></i> Pedido completado - Listo para enviar
              </span>
            @else
              <span class="badge bg-warning text-dark">
                <i class="bi bi-hourglass-split"></i> En proceso
              </span>
            @endif
          </div>
        </div>
      </div>
    </div>
  @empty
    <div class="card shadow-sm">
      <div class="card-body text-center py-5">
        <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.3; color: #6c757d;"></i>
        <h5 class="mt-3 text-muted">No hay pedidos delivery pendientes</h5>
        <p class="text-muted">Los nuevos pedidos confirmados aparecerán aquí automáticamente</p>
      </div>
    </div>
  @endforelse
</div>
@endsection

@push('styles')
<style>
  .table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
  }
  
  .card {
    transition: transform 0.2s ease;
  }
  
  .card:hover {
    transform: translateY(-2px);
  }
  
  .btn-group .btn {
    font-weight: 500;
  }
</style>
@endpush