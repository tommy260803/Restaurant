@extends('layouts.plantilla')

@section('title', 'Detalle Pedido Delivery #' . $pedido->id)

@section('contenido')
<div class="container-fluid mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4"><i class="bi bi-eye"></i> Detalle Pedido Delivery #{{ $pedido->id }}</h1>
    <div>
      <a href="{{ route('admin.delivery.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver
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

  <div class="row g-3">
    <!-- Información principal -->
    <div class="col-12 col-lg-8">
      <!-- Datos del cliente -->
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-light">
          <strong><i class="bi bi-person-circle"></i> Información del Cliente</strong>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <div><strong>Nombre:</strong> {{ $pedido->nombre_cliente }}</div>
              <div><strong>Email:</strong> {{ $pedido->email }}</div>
              <div><strong>Teléfono:</strong> {{ $pedido->telefono }}</div>
            </div>
            <div class="col-md-6">
              <div><strong>Dirección:</strong> {{ $pedido->direccion_entrega }}</div>
              @if($pedido->referencia)
                <div><strong>Referencia:</strong> {{ $pedido->referencia }}</div>
              @endif
              <div>
                <strong>Fecha/Hora Pedido:</strong> 
                {{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y') }} 
                - {{ \Carbon\Carbon::parse($pedido->hora_pedido)->format('H:i') }}
              </div>
            </div>
          </div>
          @if($pedido->comentarios)
            <hr>
            <div>
              <strong>Comentarios:</strong>
              <div class="border rounded p-2 bg-light mt-1">{{ $pedido->comentarios }}</div>
            </div>
          @endif
        </div>
      </div>

      <!-- Detalle de platos -->
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-light">
          <strong><i class="bi bi-cart-check"></i> Detalle del Pedido</strong>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Plato</th>
                  <th class="text-center">Cantidad</th>
                  <th class="text-end">P. Unit.</th>
                  <th class="text-end">Subtotal</th>
                  <th class="text-center">Estado</th>
                  <th>Notas</th>
                </tr>
              </thead>
              <tbody>
                @php $total = 0; @endphp
                @foreach($pedido->platos as $item)
                  @php 
                    $subtotal = $item->precio * $item->cantidad;
                    $total += $subtotal;
                  @endphp
                  <tr>
                    <td>
                      <strong>{{ $item->plato->nombre }}</strong>
                      @if($item->plato->descripcion)
                        <br><small class="text-muted">{{ Str::limit($item->plato->descripcion, 40) }}</small>
                      @endif
                    </td>
                    <td class="text-center">
                      <span class="badge bg-info">{{ $item->cantidad }}</span>
                    </td>
                    <td class="text-end">S/. {{ number_format($item->precio, 2) }}</td>
                    <td class="text-end"><strong>S/. {{ number_format($subtotal, 2) }}</strong></td>
                    <td class="text-center">
                      @php
                        $estadoPlatoConfig = [
                          'pendiente' => ['color' => 'secondary', 'texto' => 'Pendiente'],
                          'en_preparacion' => ['color' => 'warning', 'texto' => 'Preparando'],
                          'preparado' => ['color' => 'success', 'texto' => 'Preparado'],
                        ];
                        $estadoPlato = $estadoPlatoConfig[$item->estado] ?? $estadoPlatoConfig['pendiente'];
                      @endphp
                      <span class="badge bg-{{ $estadoPlato['color'] }}">{{ $estadoPlato['texto'] }}</span>
                    </td>
                    <td>
                      <small>{{ $item->notas ?? '—' }}</small>
                    </td>
                  </tr>
                @endforeach
              </tbody>
              <tfoot class="table-light">
                <tr>
                  <td colspan="3" class="text-end"><strong>TOTAL:</strong></td>
                  <td class="text-end"><strong class="text-success fs-5">S/. {{ number_format($total, 2) }}</strong></td>
                  <td colspan="2"></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>

      <!-- Estado del pago -->
      @if($pedido->pago)
        <div class="card shadow-sm">
          <div class="card-header bg-light">
            <strong><i class="bi bi-credit-card"></i> Información de Pago</strong>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-4">
                <div><strong>Método:</strong> <span class="badge bg-secondary text-uppercase">{{ $pedido->pago->metodo }}</span></div>
              </div>
              <div class="col-md-4">
                <div><strong>Monto:</strong> <span class="text-success fw-bold fs-5">S/. {{ number_format($pedido->pago->monto, 2) }}</span></div>
              </div>
              <div class="col-md-4">
                <div>
                  <strong>Estado:</strong> 
                  @if($pedido->pago->estado === 'confirmado')
                    <span class="badge bg-success">Confirmado</span>
                  @elseif($pedido->pago->estado === 'pendiente')
                    <span class="badge bg-warning text-dark">Pendiente</span>
                  @else
                    <span class="badge bg-danger">Rechazado</span>
                  @endif
                </div>
              </div>
            </div>
            @if($pedido->pago->numero_operacion)
              <hr>
              <div><strong>N° Operación:</strong> {{ $pedido->pago->numero_operacion }}</div>
            @endif
          </div>
        </div>
      @endif
    </div>

    <!-- Acciones -->
    <div class="col-12 col-lg-4">
      <!-- Estado del pedido -->
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-light">
          <strong>Estado del Pedido</strong>
        </div>
        <div class="card-body text-center">
          @php
            $estadoConfig = [
              'pendiente_pago' => ['color' => 'warning', 'icono' => 'clock', 'texto' => 'Pendiente de Pago'],
              'confirmado' => ['color' => 'info', 'icono' => 'check-circle', 'texto' => 'Pago Confirmado'],
              'en_preparacion' => ['color' => 'primary', 'icono' => 'fire', 'texto' => 'En Preparación'],
              'listo' => ['color' => 'success', 'icono' => 'box-seam', 'texto' => 'Listo para Enviar'],
              'en_camino' => ['color' => 'info', 'icono' => 'bicycle', 'texto' => 'En Camino'],
              'entregado' => ['color' => 'success', 'icono' => 'check-all', 'texto' => 'Entregado'],
              'cancelado' => ['color' => 'danger', 'icono' => 'x-circle', 'texto' => 'Cancelado'],
            ];
            $estado = $estadoConfig[$pedido->estado] ?? $estadoConfig['pendiente_pago'];
          @endphp
          
          <i class="bi bi-{{ $estado['icono'] }} text-{{ $estado['color'] }}" style="font-size: 3rem;"></i>
          <h4 class="mt-2 mb-0">
            <span class="badge bg-{{ $estado['color'] }}">{{ $estado['texto'] }}</span>
          </h4>
        </div>
      </div>

      <!-- Gestión de Pago -->
      @if($pedido->pago && $pedido->pago->estado === 'pendiente')
        <div class="card shadow-sm mb-3">
          <div class="card-header bg-light">
            <strong>Gestión de Pago</strong>
          </div>
          <div class="card-body d-grid gap-2">
            <form action="{{ route('admin.delivery.confirmar-pago', $pedido->id) }}" method="POST">
              @csrf
              <button class="btn btn-success w-100">
                <i class="bi bi-check-circle"></i> Confirmar Pago
              </button>
            </form>
            <form action="{{ route('admin.delivery.rechazar-pago', $pedido->id) }}" method="POST" onsubmit="return confirm('¿Rechazar este pago?')">
              @csrf
              <button class="btn btn-danger w-100">
                <i class="bi bi-x-circle"></i> Rechazar Pago
              </button>
            </form>
          </div>
        </div>
      @endif

      <!-- Cambiar estado del pedido -->
      @if($pedido->estado !== 'entregado' && $pedido->estado !== 'cancelado')
        <div class="card shadow-sm mb-3">
          <div class="card-header bg-light">
            <strong>Cambiar Estado</strong>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.delivery.cambiar-estado', $pedido->id) }}" method="POST" class="d-grid gap-2">
              @csrf
              <select name="estado" class="form-select" required>
                <option value="">-- Seleccionar --</option>
                <option value="confirmado" {{ $pedido->estado === 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                <option value="en_preparacion" {{ $pedido->estado === 'en_preparacion' ? 'selected' : '' }}>En Preparación</option>
                <option value="listo" {{ $pedido->estado === 'listo' ? 'selected' : '' }}>Listo</option>
                <option value="en_camino" {{ $pedido->estado === 'en_camino' ? 'selected' : '' }}>En Camino</option>
                <option value="entregado" {{ $pedido->estado === 'entregado' ? 'selected' : '' }}>Entregado</option>
                <option value="cancelado" {{ $pedido->estado === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
              </select>
              <button class="btn btn-primary">
                <i class="bi bi-arrow-repeat"></i> Actualizar Estado
              </button>
            </form>
          </div>
        </div>
      @endif

      <!-- Eliminar pedido -->
      <div class="card shadow-sm border-danger">
        <div class="card-header bg-light text-danger">
          <strong>Zona de Peligro</strong>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.delivery.destroy', $pedido->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este pedido? Esta acción no se puede deshacer.')">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-danger w-100">
              <i class="bi bi-trash"></i> Eliminar Pedido
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection