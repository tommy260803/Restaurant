@extends('layouts.plantilla')

@section('title', 'Pagos de Reservas')

@section('contenido')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-credit-card"></i> Pagos de Reservas</h1>
        <a href="{{ route('reservas.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Reservas
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtros -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label">Buscar</label>
            <input type="text" name="q" class="form-control" placeholder="ID pago, ID reserva o Nº operación" value="{{ $q ?? '' }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
                <option value="">Todos</option>
                <option value="pendiente" {{ ($estado ?? '')==='pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="confirmado" {{ ($estado ?? '')==='confirmado' ? 'selected' : '' }}>Confirmado</option>
                <option value="fallido" {{ ($estado ?? '')==='fallido' ? 'selected' : '' }}>Fallido</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Desde</label>
            <input type="date" name="desde" class="form-control" value="{{ $desde ?? '' }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">Hasta</label>
            <input type="date" name="hasta" class="form-control" value="{{ $hasta ?? '' }}">
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button class="btn btn-outline-primary w-100"><i class="bi bi-filter"></i> Filtrar</button>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Reserva</th>
                            <th>Método</th>
                            <th>Nº Operación</th>
                            <th class="text-end">Monto</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pagos as $pago)
                            <tr>
                                <td>{{ $pago->id }}</td>
                                <td>
                                    @if($pago->reserva)
                                        <div>
                                            <div><strong>#{{ $pago->reserva_id }}</strong> — {{ $pago->reserva->nombre_cliente }}</div>
                                            <small class="text-muted">{{ $pago->reserva->fecha_reserva }} {{ $pago->reserva->hora_reserva }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ ucfirst($pago->metodo) }}</td>
                                <td>{{ $pago->numero_operacion ?? '—' }}</td>
                                <td class="text-end">S/ {{ number_format($pago->monto, 2) }}</td>
                                <td>
                                    <form action="{{ route('caja.pagos.actualizar-estado', $pago->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                        @csrf
                                        <select name="estado" class="form-select form-select-sm w-auto">
                                            <option value="pendiente" {{ $pago->estado==='pendiente' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="confirmado" {{ $pago->estado==='confirmado' ? 'selected' : '' }}>Confirmado</option>
                                            <option value="fallido" {{ $pago->estado==='fallido' ? 'selected' : '' }}>Fallido</option>
                                        </select>
                                        <button class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center" title="Actualizar estado">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>{{ $pago->fecha }}</td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('reserva.pdf', $pago->reserva_id) }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center" title="Descargar PDF">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </a>
                                        <form action="{{ route('reserva.reenviar-email', $pago->reserva_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-outline-primary d-flex align-items-center justify-content-center" title="Reenviar correo">
                                                <i class="bi bi-envelope-arrow-up"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-emoji-neutral" style="font-size: 2rem"></i>
                                    <div>No hay pagos registrados</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                {{ $pagos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
