@extends('layouts.plantilla')

@section('title', 'Cocina - Detalle de Pedido')

@section('contenido')
<div class="container-fluid mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4"><i class="bi bi-eye"></i> Detalle de Pedido</h1>
    <div>
      <a href="{{ route('cocinero.pedidos') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-12 col-lg-8">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <div><strong>Mesa:</strong> {{ $pedido->reserva?->mesa?->numero ? 'Mesa '.$pedido->reserva->mesa->numero : '—' }}</div>
              <div><strong>Cliente:</strong> {{ $pedido->reserva?->nombre_cliente ?? '—' }}</div>
              <div><strong>Personas:</strong> {{ $pedido->reserva?->numero_personas ?? '—' }}</div>
            </div>
            <div class="col-md-6">
              <div><strong>Hora ingreso:</strong> {{ $pedido->created_at?->format('H:i') }}</div>
              <div><strong>Estado:</strong> {{ $pedido->estado }}</div>
            </div>
          </div>
          <hr>
          <div class="row g-3">
            <div class="col-md-8">
              <div><strong>Plato:</strong> {{ $pedido->plato?->nombre ?? '—' }}</div>
              <div class="text-muted small">{{ $pedido->plato?->descripcion ?? '' }}</div>
            </div>
            <div class="col-md-4">
              <div><strong>Cantidad:</strong> {{ $pedido->cantidad }}</div>
              <div><strong>Precio:</strong> S/ {{ number_format($pedido->precio, 2) }}</div>
            </div>
          </div>
          <hr>
          <div>
            <strong>Notas:</strong>
            <div class="border rounded p-2 bg-light">{{ $pedido->notas ?? '—' }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-4">
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-light"><strong>Acciones</strong></div>
        <div class="card-body d-grid gap-2">
          <form action="{{ route('cocinero.pedidos.preparar', $pedido->id) }}" method="POST">@csrf
            <button class="btn btn-outline-primary w-100"><i class="bi bi-fire"></i> Marcar En preparación</button>
          </form>
          <form action="{{ route('cocinero.pedidos.finalizar', $pedido->id) }}" method="POST">@csrf
            <button class="btn btn-outline-success w-100"><i class="bi bi-check2"></i> Marcar Preparado</button>
          </form>
        </div>
      </div>
      <div class="card shadow-sm">
        <div class="card-header bg-light"><strong>Incidencia</strong></div>
        <div class="card-body">
          <form action="{{ route('cocinero.pedidos.incidencia', $pedido->id) }}" method="POST" class="d-grid gap-2">
            @csrf
            <textarea name="notas" rows="4" class="form-control" placeholder="Describe la incidencia (faltan insumos, etc.)">{{ old('notas', $pedido->notas) }}</textarea>
            <button class="btn btn-outline-danger"><i class="bi bi-flag"></i> Registrar Incidencia</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
