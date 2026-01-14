@extends('layouts.plantilla')

@section('contenido')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h3 class="text-primary"><i class="bi bi-clock-history me-2"></i>Historial Financiero del Proveedor</h3>
            <small class="text-muted">{{ $proveedor->nombre }} {{ $proveedor->apellidoPaterno }}</small>
        </div>
    </div>

    <!-- Estadísticas Resumen -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary h-100">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-1">Total de Compras</h6>
                    <h3 class="card-text text-primary">{{ $cantidadCompras }}</h3>
                    <small class="text-success">Transacciones registradas</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-success h-100">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-1">Monto Acumulado</h6>
                    <h3 class="card-text text-success">S/ {{ number_format($totalCompras, 2) }}</h3>
                    <small class="text-info">Monto total invertido</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-warning h-100">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-1">Promedio por Compra</h6>
                    <h3 class="card-text text-warning">S/ {{ number_format($cantidadCompras > 0 ? $totalCompras / $cantidadCompras : 0, 2) }}</h3>
                    <small class="text-secondary">Valor promedio</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Compras -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-table me-2"></i>Detalle de Compras</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Concepto</th>
                                <th>Monto (S/.)</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($compras as $compra)
                            <tr>
                                <td><strong>{{ $loop->iteration }}</strong></td>
                                <td>{{ $compra->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $compra->concepto ?? 'Compra general' }}</td>
                                <td class="text-end"><strong>{{ number_format($compra->total, 2) }}</strong></td>
                                <td>
                                    <small class="text-muted">
                                        {{ Str::limit($compra->descripcion ?? 'Sin descripción', 40) }}
                                    </small>
                                </td>
                                <td>
                                    @if($compra->estado === 'completada')
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Completada</span>
                                    @elseif($compra->estado === 'pendiente')
                                        <span class="badge bg-warning"><i class="bi bi-hourglass me-1"></i>Pendiente</span>
                                    @elseif($compra->estado === 'cancelada')
                                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Cancelada</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($compra->estado ?? 'Desconocido') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mt-2">No hay compras registradas para este proveedor</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if($compras->hasPages())
                <div class="card-footer">
                    {{ $compras->links('pagination::bootstrap-4') }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Gráfico de Compras por Mes (opcional) -->
    @if($cantidadCompras > 0)
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>Análisis de Compras</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Distribución por Estado</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <span class="badge bg-success">Completadas</span>
                                    <strong>{{ $compras->where('estado', 'completada')->count() }}</strong>
                                    ({{ round($compras->where('estado', 'completada')->count() / $cantidadCompras * 100) }}%)
                                </li>
                                <li class="mb-2">
                                    <span class="badge bg-warning">Pendientes</span>
                                    <strong>{{ $compras->where('estado', 'pendiente')->count() }}</strong>
                                    ({{ round($compras->where('estado', 'pendiente')->count() / $cantidadCompras * 100) }}%)
                                </li>
                                <li class="mb-2">
                                    <span class="badge bg-danger">Canceladas</span>
                                    <strong>{{ $compras->where('estado', 'cancelada')->count() }}</strong>
                                    ({{ round($compras->where('estado', 'cancelada')->count() / $cantidadCompras * 100) }}%)
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Detalles Financieros</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <strong>Monto Total:</strong> S/ {{ number_format($totalCompras, 2) }}
                                </li>
                                <li class="mb-2">
                                    <strong>Promedio por Compra:</strong> S/ {{ number_format($cantidadCompras > 0 ? $totalCompras / $cantidadCompras : 0, 2) }}
                                </li>
                                <li class="mb-2">
                                    <strong>Mayor Compra:</strong> S/ {{ number_format($compras->max('total') ?? 0, 2) }}
                                </li>
                                <li class="mb-2">
                                    <strong>Menor Compra:</strong> S/ {{ number_format($compras->min('total') ?? 0, 2) }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Botones de Acción -->
    <div class="row mt-4">
        <div class="col-md-12 d-flex gap-2">
            <a href="{{ route('proveedor.dashboard', $proveedor->idProveedor) }}" class="btn btn-info">
                <i class="bi bi-graph-up me-2"></i>Dashboard
            </a>
            <a href="{{ route('proveedor.edit', $proveedor->idProveedor) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Editar
            </a>
            <a href="{{ route('proveedor.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
</div>

<style>
    .border-left-primary { border-left: 4px solid #0066cc !important; }
    .border-left-success { border-left: 4px solid #28a745 !important; }
    .border-left-warning { border-left: 4px solid #ffc107 !important; }
    .border-left-info { border-left: 4px solid #17a2b8 !important; }
</style>

@endsection
