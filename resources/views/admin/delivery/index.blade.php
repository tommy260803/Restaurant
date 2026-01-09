@extends('layouts.plantilla')

@section('title', 'Administración - Pedidos Delivery')

@section('contenido')
<div class="container-fluid mt-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4"><i class="bi bi-truck"></i> Gestión de Pedidos Delivery</h1>
    <div>
      <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
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

  <!-- Estadísticas -->
  <div class="row g-3 mb-4">
    <div class="col-12 col-md-3">
      <div class="card shadow-sm border-start border-warning border-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted mb-1 small">Pendiente Pago</p>
              <h3 class="mb-0 fw-bold text-warning">{{ $pedidos->where('estado', 'pendiente_pago')->count() }}</h3>
            </div>
            <i class="bi bi-clock text-warning" style="font-size: 2rem;"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card shadow-sm border-start border-info border-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted mb-1 small">En Preparación</p>
              <h3 class="mb-0 fw-bold text-info">{{ $pedidos->where('estado', 'en_preparacion')->count() }}</h3>
            </div>
            <i class="bi bi-fire text-info" style="font-size: 2rem;"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card shadow-sm border-start border-primary border-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted mb-1 small">En Camino</p>
              <h3 class="mb-0 fw-bold text-primary">{{ $pedidos->where('estado', 'en_camino')->count() }}</h3>
            </div>
            <i class="bi bi-bicycle text-primary" style="font-size: 2rem;"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card shadow-sm border-start border-success border-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted mb-1 small">Entregados Hoy</p>
              <h3 class="mb-0 fw-bold text-success">{{ $pedidos->where('estado', 'entregado')->count() }}</h3>
            </div>
            <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filtros -->
  <div class="card shadow-sm mb-3">
    <div class="card-header bg-light">
      <i class="bi bi-funnel"></i> <strong>Filtros</strong>
    </div>
    <div class="card-body">
      <form class="row g-2" method="GET">
        <div class="col-auto">
          <select name="estado" class="form-select">
            <option value="">Todos los estados</option>
            <option value="pendiente_pago">Pendiente Pago</option>
            <option value="confirmado">Confirmado</option>
            <option value="en_preparacion">En Preparación</option>
            <option value="listo">Listo</option>
            <option value="en_camino">En Camino</option>
            <option value="entregado">Entregado</option>
            <option value="cancelado">Cancelado</option>
          </select>
        </div>
        <div class="col-auto">
          <input type="date" name="fecha" class="form-control" placeholder="Fecha">
        </div>
        <div class="col-auto">
          <button class="btn btn-primary"><i class="bi bi-search"></i> Filtrar</button>
        </div>
        <div class="col-auto">
          <a href="{{ route('admin.delivery.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-x-circle"></i> Limpiar
          </a>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabla de pedidos -->
  <div class="card shadow-sm">
    <div class="card-header bg-light">
      <strong>Lista de Pedidos Delivery</strong>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover table-sm align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width: 5%;">#</th>
              <th style="width: 10%;">Fecha</th>
              <th style="width: 15%;">Cliente</th>
              <th style="width: 12%;">Teléfono</th>
              <th style="width: 20%;">Dirección</th>
              <th class="text-center" style="width: 10%;">Total</th>
              <th class="text-center" style="width: 10%;">Pago</th>
              <th class="text-center" style="width: 10%;">Estado</th>
              <th class="text-end" style="width: 8%;">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pedidos as $pedido)
              <tr>
                <td class="fw-bold">#{{ $pedido->id }}</td>
                <td>
                  <small class="text-muted">
                    {{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y') }}
                    <br>{{ \Carbon\Carbon::parse($pedido->hora_pedido)->format('H:i') }}
                  </small>
                </td>
                <td>
                  <strong>{{ $pedido->nombre_cliente }}</strong>
                  <br><small class="text-muted">{{ $pedido->email }}</small>
                </td>
                <td>{{ $pedido->telefono }}</td>
                <td>
                  <small>{{ Str::limit($pedido->direccion_entrega, 30) }}</small>
                </td>
                <td class="text-center">
                  @php
                    $total = $pedido->platos->sum(function($item) {
                      return $item->precio * $item->cantidad;
                    });
                  @endphp
                  <strong class="text-success">S/. {{ number_format($total, 2) }}</strong>
                </td>
                <td class="text-center">
                  @if($pedido->pago)
                    @if($pedido->pago->estado === 'confirmado')
                      <span class="badge bg-success">Confirmado</span>
                    @elseif($pedido->pago->estado === 'pendiente')
                      <span class="badge bg-warning text-dark">Pendiente</span>
                    @else
                      <span class="badge bg-danger">Rechazado</span>
                    @endif
                  @else
                    <span class="badge bg-secondary">Sin pago</span>
                  @endif
                </td>
                <td class="text-center">
                  @php
                    $estadoConfig = [
                      'pendiente_pago' => ['color' => 'warning', 'texto' => 'Pend. Pago'],
                      'confirmado' => ['color' => 'info', 'texto' => 'Confirmado'],
                      'en_preparacion' => ['color' => 'primary', 'texto' => 'Preparando'],
                      'listo' => ['color' => 'success', 'texto' => 'Listo'],
                      'en_camino' => ['color' => 'info', 'texto' => 'En Camino'],
                      'entregado' => ['color' => 'success', 'texto' => 'Entregado'],
                      'cancelado' => ['color' => 'danger', 'texto' => 'Cancelado'],
                    ];
                    $estado = $estadoConfig[$pedido->estado] ?? ['color' => 'secondary', 'texto' => $pedido->estado];
                  @endphp
                  <span class="badge bg-{{ $estado['color'] }}">{{ $estado['texto'] }}</span>
                </td>
                <td class="text-end">
                  <div class="btn-group btn-group-sm">
                    <a href="{{ route('admin.delivery.show', $pedido->id) }}" class="btn btn-outline-secondary" title="Ver detalle">
                      <i class="bi bi-eye"></i>
                    </a>
                    @if($pedido->estado !== 'entregado' && $pedido->estado !== 'cancelado')
                      <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEstado{{ $pedido->id }}" title="Cambiar estado">
                        <i class="bi bi-arrow-repeat"></i>
                      </button>
                    @endif
                    <form action="{{ route('admin.delivery.destroy', $pedido->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este pedido?')">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-outline-danger" title="Eliminar">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>

              <!-- Modal cambiar estado -->
              <div class="modal fade" id="modalEstado{{ $pedido->id }}" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Cambiar Estado - Pedido #{{ $pedido->id }}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.delivery.cambiar-estado', $pedido->id) }}" method="POST">
                      @csrf
                      <div class="modal-body">
                        <div class="mb-3">
                          <label class="form-label fw-bold">Nuevo Estado</label>
                          <select name="estado" class="form-select" required>
                            <option value="confirmado" {{ $pedido->estado === 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                            <option value="en_preparacion" {{ $pedido->estado === 'en_preparacion' ? 'selected' : '' }}>En Preparación</option>
                            <option value="listo" {{ $pedido->estado === 'listo' ? 'selected' : '' }}>Listo</option>
                            <option value="en_camino" {{ $pedido->estado === 'en_camino' ? 'selected' : '' }}>En Camino</option>
                            <option value="entregado" {{ $pedido->estado === 'entregado' ? 'selected' : '' }}>Entregado</option>
                            <option value="cancelado" {{ $pedido->estado === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                          </select>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            @empty
              <tr>
                <td colspan="9" class="text-center text-muted py-5">
                  <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                  <div class="mt-2">No hay pedidos delivery registrados</div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  .table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
  }
</style>
@endpush