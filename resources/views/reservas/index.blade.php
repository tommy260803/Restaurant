@extends('layouts.plantilla')

@section('title', 'Gestión de Reservas')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-calendar-event"></i> Gestión de Reservas</h1>
        <a href="{{ route('reservas.create') }}" class="btn btn-primary" target="_blank">
            <i class="bi bi-plus-circle"></i> Nueva Reserva
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Reservas de Hoy -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="bi bi-calendar-day"></i> Reservas de Hoy 
                <span class="badge bg-light text-primary">{{ $reservasHoy->count() }}</span>
            </h4>
        </div>
        <div class="card-body">
            @if($reservasHoy->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
                    <p class="mt-2">No hay reservas para hoy</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-clock"></i> Hora</th>
                                <th><i class="bi bi-person"></i> Cliente</th>
                                <th><i class="bi bi-telephone"></i> Teléfono</th>
                                <th><i class="bi bi-people"></i> Personas</th>
                                <th><i class="bi bi-table"></i> Mesa</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservasHoy as $reserva)
                            <tr>
                                <td><strong>{{ $reserva->hora_formateada }}</strong></td>
                                <td>{{ $reserva->nombre_cliente }}</td>
                                <td>{{ $reserva->telefono }}</td>
                                <td><span class="badge bg-info">{{ $reserva->numero_personas }}</span></td>
                                <td>
                                    @if($reserva->mesa_id)
                                        <span class="badge bg-secondary">Mesa {{ $reserva->mesa->numero }}</span>
                                    @else
                                        <span class="text-muted">Sin asignar</span>
                                    @endif
                                </td>
                                <td>
                                    @if($reserva->estado == 'pendiente')
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    @elseif($reserva->estado == 'confirmada')
                                        <span class="badge bg-success">Confirmada</span>
                                    @elseif($reserva->estado == 'completada')
                                        <span class="badge bg-info">Completada</span>
                                    @else
                                        <span class="badge bg-danger">Cancelada</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if($reserva->estado == 'pendiente')
                                            <form action="{{ route('reservas.confirmar', $reserva->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-success btn-sm" title="Confirmar">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($reserva->estado == 'confirmada')
                                            <form action="{{ route('reservas.completar', $reserva->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-info btn-sm" title="Cliente llegó">
                                                    <i class="bi bi-person-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($reserva->estado != 'cancelada' && $reserva->estado != 'completada')
                                            <form action="{{ route('reservas.cancelar', $reserva->id) }}" method="POST" class="d-inline" 
                                                  onsubmit="return confirm('¿Cancelar esta reserva?')">
                                                @csrf
                                                <button class="btn btn-danger btn-sm" title="Cancelar">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Próximas Reservas -->
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h4 class="mb-0">
                <i class="bi bi-calendar-range"></i> Próximas Reservas 
                <span class="badge bg-light text-dark">{{ $reservasProximas->count() }}</span>
            </h4>
        </div>
        <div class="card-body">
            @if($reservasProximas->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
                    <p class="mt-2">No hay reservas próximas</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Cliente</th>
                                <th>Teléfono</th>
                                <th>Personas</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservasProximas as $reserva)
                            <tr>
                                <td>{{ $reserva->fecha_formateada }}</td>
                                <td>{{ $reserva->hora_formateada }}</td>
                                <td>{{ $reserva->nombre_cliente }}</td>
                                <td>{{ $reserva->telefono }}</td>
                                <td><span class="badge bg-info">{{ $reserva->numero_personas }}</span></td>
                                <td>
                                    @if($reserva->estado == 'pendiente')
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    @else
                                        <span class="badge bg-success">Confirmada</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection