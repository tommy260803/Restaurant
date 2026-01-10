{{-- cocinero/historial.blade.php--}}
@extends('layouts.plantilla')

@section('title', 'Cocina - Historial')

@section('contenido')
<div class="container-fluid mt-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4"><i class="bi bi-clock-history"></i> Historial de Pedidos</h1>
    <div>
      <a href="{{ route('cocinero.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Dashboard
      </a>
    </div>
  </div>

  <!-- Tarjeta de filtros -->
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
      <i class="bi bi-funnel"></i> <strong>Filtros</strong>
    </div>
    <div class="card-body">
      <form class="row g-3" method="GET">
        <div class="col-md-3">
          <label class="form-label">Desde</label>
          <input type="date" name="desde" class="form-control" value="{{ $desde ?? '' }}">
        </div>
        <div class="col-md-3">
          <label class="form-label">Hasta</label>
          <input type="date" name="hasta" class="form-control" value="{{ $hasta ?? '' }}">
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <button type="submit" class="btn btn-primary me-2">
            <i class="bi bi-search"></i> Filtrar
          </button>
          <a href="{{ route('cocinero.historial') }}" class="btn btn-outline-secondary">
            <i class="bi bi-x-circle"></i> Limpiar
          </a>
        </div>
      </form>
    </div>
  </div>

  <!-- Resumen de resultados -->
  @if($pedidos->total() > 0)
    <div class="alert alert-info mb-3">
      <i class="bi bi-info-circle"></i> 
      Mostrando <strong>{{ $pedidos->count() }}</strong> de <strong>{{ $pedidos->total() }}</strong> pedidos preparados
    </div>
  @endif

  <!-- Tabla de pedidos -->
  <div class="card shadow-sm">
    <div class="card-header bg-light">
      <strong>Pedidos Preparados</strong>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width: 10%;">Fecha</th>
              <th style="width: 10%;">Hora Prep.</th>
              <th style="width: 10%;">Mesa</th>
              <th style="width: 20%;">Cliente</th>
              <th style="width: 25%;">Plato</th>
              <th class="text-center" style="width: 8%;">Cant.</th>
              <th style="width: 12%;">Duración</th>
              <th class="text-center" style="width: 5%;">Estado</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pedidos as $p)
              <tr>
                <td>
                  <small class="text-muted">
                    {{ $p->reserva?->fecha_reserva?->format('d/m/Y') ?? '—' }}
                  </small>
                </td>
                <td>
                  <span class="badge bg-success">
                    <i class="bi bi-check-circle"></i>
                    {{ $p->preparado_at?->format('H:i') ?? '—' }}
                  </span>
                </td>
                <td>
                  @if($p->reserva?->mesa?->numero)
                    <span class="badge bg-secondary">
                      <i class="bi bi-table"></i> Mesa {{ $p->reserva->mesa->numero }}
                    </span>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td>
                  <strong>{{ $p->reserva?->nombre_cliente ?? '—' }}</strong>
                </td>
                <td>
                  <div class="d-flex align-items-center">
                    <i class="bi bi-dish text-primary me-2"></i>
                    <span>{{ $p->plato?->nombre ?? '—' }}</span>
                  </div>
                </td>
                <td class="text-center">
                  <span class="badge bg-info">{{ $p->cantidad }}</span>
                </td>
                <td>
                  @php
                    $dur = null;
                    $durMinutos = 0;
                    if ($p->en_preparacion_at && $p->preparado_at) {
                      $diff = $p->en_preparacion_at->diff($p->preparado_at);
                      $durMinutos = ($diff->h * 60) + $diff->i;
                      $dur = $diff->format('%H:%I:%S');
                    }
                  @endphp
                  @if($dur)
                    <span class="badge {{ $durMinutos <= 15 ? 'bg-success' : ($durMinutos <= 30 ? 'bg-warning' : 'bg-danger') }}">
                      <i class="bi bi-stopwatch"></i> {{ $dur }}
                    </span>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td class="text-center">
                  <i class="bi bi-check-circle-fill text-success" title="Preparado"></i>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center text-muted py-5">
                  <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                  <div class="mt-2">No hay pedidos preparados en el rango seleccionado</div>
                  <small>Intenta ajustar los filtros de fecha</small>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    
    @if($pedidos->hasPages())
      <div class="card-footer bg-light">
        <div class="d-flex justify-content-between align-items-center">
          <div class="text-muted small">
            Página {{ $pedidos->currentPage() }} de {{ $pedidos->lastPage() }}
          </div>
          <div>
            {{ $pedidos->links() }}
          </div>
        </div>
      </div>
    @endif
  </div>

  <!-- Estadísticas rápidas (opcional) -->
  @if($pedidos->total() > 0)
    <div class="row g-3 mt-4">
      <div class="col-md-4">
        <div class="card border-success">
          <div class="card-body text-center">
            <i class="bi bi-check2-all text-success" style="font-size: 2rem;"></i>
            <h5 class="mt-2">{{ $pedidos->total() }}</h5>
            <small class="text-muted">Pedidos Preparados</small>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-info">
          <div class="card-body text-center">
            <i class="bi bi-clock text-info" style="font-size: 2rem;"></i>
            <h5 class="mt-2">
              @php
                $promedio = 0;
                $count = 0;
                foreach($pedidos as $p) {
                  if ($p->en_preparacion_at && $p->preparado_at) {
                    $promedio += $p->en_preparacion_at->diffInMinutes($p->preparado_at);
                    $count++;
                  }
                }
                $promedio = $count > 0 ? round($promedio / $count) : 0;
              @endphp
              {{ $promedio }} min
            </h5>
            <small class="text-muted">Tiempo Promedio</small>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-warning">
          <div class="card-body text-center">
            <i class="bi bi-speedometer text-warning" style="font-size: 2rem;"></i>
            <h5 class="mt-2">
              @php
                $rapido = PHP_INT_MAX;
                foreach($pedidos as $p) {
                  if ($p->en_preparacion_at && $p->preparado_at) {
                    $mins = $p->en_preparacion_at->diffInMinutes($p->preparado_at);
                    if ($mins < $rapido) $rapido = $mins;
                  }
                }
                $rapido = $rapido === PHP_INT_MAX ? 0 : $rapido;
              @endphp
              {{ $rapido }} min
            </h5>
            <small class="text-muted">Más Rápido</small>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
@endsection

@push('styles')
<style>
  .table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
    cursor: pointer;
  }
  
  .badge {
    font-weight: 500;
  }
</style>
@endpush