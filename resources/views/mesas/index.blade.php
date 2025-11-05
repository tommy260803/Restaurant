@extends('layouts.plantilla')

@section('title', 'Mesas')

@section('contenido')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-table"></i> Mesas</h1>
        <div class="text-muted">
            <span class="me-3"><strong>Total:</strong> {{ $estadisticas['total'] }}</span>
            <span class="me-3 text-success"><strong>Disponibles:</strong> {{ $estadisticas['disponibles'] }}</span>
            <span class="me-3 text-warning"><strong>Reservadas:</strong> {{ $estadisticas['reservadas'] }}</span>
            <span class="me-3 text-info"><strong>Ocupadas:</strong> {{ $estadisticas['ocupadas'] }}</span>
            <span class="text-secondary"><strong>Mantenimiento:</strong> {{ $estadisticas['mantenimiento'] }}</span>
        </div>
        <a href="{{ route('mesas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nueva Mesa
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Número</th>
                            <th>Capacidad</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mesas as $mesa)
                            <tr>
                                <td>{{ $mesa->id }}</td>
                                <td><strong>{{ $mesa->numero }}</strong></td>
                                <td><span class="badge bg-primary">{{ $mesa->capacidad }}</span></td>
                                <td>
                                    @if($mesa->estado === 'disponible')
                                        <span class="badge bg-success">Disponible</span>
                                    @elseif($mesa->estado === 'reservada')
                                        <span class="badge bg-warning text-dark">Reservada</span>
                                    @elseif($mesa->estado === 'ocupada')
                                        <span class="badge bg-info">Ocupada</span>
                                    @else
                                        <span class="badge bg-secondary">Mantenimiento</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <form action="{{ route('mesas.cambiar-estado', $mesa) }}" method="POST" class="d-flex align-items-center gap-2">
                                            @csrf
                                            <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="disponible" {{ $mesa->estado==='disponible'?'selected':'' }}>Disponible</option>
                                                <option value="reservada" {{ $mesa->estado==='reservada'?'selected':'' }}>Reservada</option>
                                                <option value="ocupada" {{ $mesa->estado==='ocupada'?'selected':'' }}>Ocupada</option>
                                                <option value="mantenimiento" {{ $mesa->estado==='mantenimiento'?'selected':'' }}>Mantenimiento</option>
                                            </select>
                                        </form>
                                        <a href="{{ route('mesas.edit', $mesa) }}" class="btn btn-sm btn-outline-secondary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('mesas.destroy', $mesa) }}" method="POST" onsubmit="return confirm('¿Eliminar esta mesa?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-emoji-neutral" style="font-size: 2rem"></i>
                                    <div>No hay mesas registradas</div>
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
