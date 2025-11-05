@extends('layouts.plantilla')

@section('title', 'Cocina - Pedidos')

@section('contenido')
<div class="container-fluid mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4"><i class="bi bi-list-task"></i> Pedidos</h1>
    <div>
      <a href="{{ route('cocinero.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Dashboard</a>
    </div>
  </div>

  <form class="row g-2 mb-3" method="GET">
    <div class="col-auto">
      <select name="estado" class="form-select">
        <option value="">Todos</option>
        <option value="Enviado a cocina" {{ $estado==='Enviado a cocina' ? 'selected' : '' }}>Enviado a cocina</option>
        <option value="En preparación" {{ $estado==='En preparación' ? 'selected' : '' }}>En preparación</option>
        <option value="Preparado" {{ $estado==='Preparado' ? 'selected' : '' }}>Preparado</option>
      </select>
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-primary"><i class="bi bi-filter"></i> Filtrar</button>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-sm align-middle">
      <thead>
        <tr>
          <th>Hora</th>
          <th>Mesa</th>
          <th>Cliente</th>
          <th>Plato</th>
          <th class="text-center">Cant.</th>
          <th>Estado</th>
          <th>Notas</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($pedidos as $p)
          <tr>
            <td>{{ $p->created_at?->format('H:i') }}</td>
            <td>{{ $p->reserva?->mesa?->numero ? 'Mesa '.$p->reserva->mesa->numero : '—' }}</td>
            <td>{{ $p->reserva?->nombre_cliente ?? '—' }}</td>
            <td>{{ $p->plato?->nombre ?? '—' }}</td>
            <td class="text-center">{{ $p->cantidad }}</td>
            <td>
              @if($p->estado==='Enviado a cocina')
                <span class="badge bg-warning text-dark">{{ $p->estado }}</span>
              @elseif($p->estado==='En preparación')
                <span class="badge bg-info text-dark">{{ $p->estado }}</span>
              @else
                <span class="badge bg-success">{{ $p->estado }}</span>
              @endif
            </td>
            <td>{{ $p->notas ?? '—' }}</td>
            <td class="text-end">
              <div class="btn-group btn-group-sm">
                <a href="{{ route('cocinero.pedidos.detalle', $p->id) }}" class="btn btn-outline-secondary" title="Detalle"><i class="bi bi-eye"></i></a>
                @if($p->estado==='Enviado a cocina')
                  <form action="{{ route('cocinero.pedidos.preparar', $p->id) }}" method="POST">@csrf<button class="btn btn-outline-primary" title="En preparación"><i class="bi bi-fire"></i></button></form>
                @endif
                @if($p->estado!=='Preparado')
                  <form action="{{ route('cocinero.pedidos.finalizar', $p->id) }}" method="POST">@csrf<button class="btn btn-outline-success" title="Preparado"><i class="bi bi-check2"></i></button></form>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="text-center text-muted py-4">No hay pedidos</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-3">{{ $pedidos->links() }}</div>
</div>
@endsection
