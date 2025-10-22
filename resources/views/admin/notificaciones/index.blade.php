@extends('layouts.plantilla') {{-- Ajusta según tu layout base --}}

@section('titulo', 'Listado de Notificaciones')

@section('contenido')
    <div class="container py-4">
        <h2 class="mb-4">Todas las notificaciones</h2>

        {{-- Filtros --}}
        <form method="GET" action="{{ route('notificaciones.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar..."
                    value="{{ request('buscar') }}">
            </div>
            <div class="col-md-2">
                <select name="tipo" class="form-select">
                    <option value="">-- Tipo --</option>
                    <option value="pago" {{ request('tipo') == 'pago' ? 'selected' : '' }}>Pago</option>
                    <option value="validacion" {{ request('tipo') == 'validacion' ? 'selected' : '' }}>Validación</option>
                    <option value="tramite" {{ request('tipo') == 'tramite' ? 'selected' : '' }}>Trámite</option>
                    <option value="vencimiento" {{ request('tipo') == 'vencimiento' ? 'selected' : '' }}>Vencimiento
                    </option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="estado" class="form-select">
                    <option value="">-- Estado --</option>
                    <option value="leida" {{ request('estado') == 'leida' ? 'selected' : '' }}>Leídas</option>
                    <option value="no_leida" {{ request('estado') == 'no_leida' ? 'selected' : '' }}>No leídas</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="prioridad" class="form-select">
                    <option value="">-- Prioridad --</option>
                    <option value="alta" {{ request('prioridad') == 'alta' ? 'selected' : '' }}>Alta</option>
                    <option value="media" {{ request('prioridad') == 'media' ? 'selected' : '' }}>Media</option>
                    <option value="baja" {{ request('prioridad') == 'baja' ? 'selected' : '' }}>Baja</option>
                </select>
            </div>
            <div class="col-md-3 text-end">
                <button class="btn btn-primary">Filtrar</button>
            </div>
        </form>

        {{-- Tabla de notificaciones --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Usuario</th>
                        <th>Título</th>
                        <th>Mensaje</th>
                        <th>Tipo</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($notificaciones as $notificacion)
                        <tr>
                            <td>{{ $notificacion->usuario->nombre_completo ?? '—' }}</td>
                            <td>{{ $notificacion->titulo }}</td>
                            <td>{{ $notificacion->mensaje }}</td>
                            <td><span class="badge bg-{{ $notificacion->color }}">{{ $notificacion->tipo }}</span></td>
                            <td>{{ ucfirst($notificacion->prioridad) }}</td>
                            <td>
                                @if ($notificacion->leida)
                                    <span class="badge bg-success">Leída</span>
                                @else
                                    <span class="badge bg-warning text-dark">No leída</span>
                                @endif
                            </td>
                            <td>{{ $notificacion->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay notificaciones encontradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="d-flex justify-content-center">
            {{ $notificaciones->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
