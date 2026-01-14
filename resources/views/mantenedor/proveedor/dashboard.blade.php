@extends('layouts.plantilla')

@section('contenido')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h3 class="text-primary"><i class="bi bi-graph-up me-2"></i>Dashboard del Proveedor</h3>
            <small class="text-muted">{{ $proveedor->nombre }} {{ $proveedor->apellidoPaterno }}</small>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Información General -->
        <div class="col-md-3">
            <div class="card h-100 border-left-primary">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-1">Total Compras</h6>
                    <h3 class="card-text text-primary">{{ $estadisticas['cantidad_compras'] }}</h3>
                    <small class="text-success">{{ count($compras) }} recientes</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-left-success">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-1">Monto Total</h6>
                    <h3 class="card-text text-success">S/ {{ number_format($estadisticas['total_compras'], 2) }}</h3>
                    <small class="text-info">Invertido totalmente</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-left-warning">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-1">Promedio por Compra</h6>
                    <h3 class="card-text text-warning">S/ {{ number_format($estadisticas['promedio_compra'] ?? 0, 2) }}</h3>
                    <small class="text-secondary">Valor promedio</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-left-info">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-1">Calificación</h6>
                    <h3 class="card-text text-info">{{ $calificaciones['promedio'] ?? 0 }}/5 ⭐</h3>
                    <small class="text-secondary">Evaluación general</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Proveedor -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-person me-2"></i>Información General</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tbody>
                            <tr>
                                <td><strong>Nombre:</strong></td>
                                <td>{{ $proveedor->nombre }} {{ $proveedor->apellidoPaterno }} {{ $proveedor->apellidoMaterno }}</td>
                            </tr>
                            <tr>
                                <td><strong>RUC:</strong></td>
                                <td>{{ $proveedor->rucProveedor ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $proveedor->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Teléfono:</strong></td>
                                <td>{{ $proveedor->telefono }}</td>
                            </tr>
                            <tr>
                                <td><strong>Dirección:</strong></td>
                                <td>{{ $proveedor->direccion }}</td>
                            </tr>
                            <tr>
                                <td><strong>Estado:</strong></td>
                                <td>
                                    @if($proveedor->estado === 'activo')
                                        <span class="badge bg-success">Activo</span>
                                    @elseif($proveedor->estado === 'bloqueado')
                                        <span class="badge bg-danger">Bloqueado</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Calificaciones Detalladas -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-star me-2"></i>Detalle de Calificaciones</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td><strong>Puntualidad:</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                            <div class="progress-bar bg-success" style="width: {{ ($calificaciones['puntualidad'] ?? 0) * 20 }}%"></div>
                                        </div>
                                        <span class="badge bg-success">{{ $calificaciones['puntualidad'] ?? 0 }}/5</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Calidad:</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                            <div class="progress-bar bg-info" style="width: {{ ($calificaciones['calidad'] ?? 0) * 20 }}%"></div>
                                        </div>
                                        <span class="badge bg-info">{{ $calificaciones['calidad'] ?? 0 }}/5</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Precio:</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                            <div class="progress-bar bg-warning" style="width: {{ ($calificaciones['precio'] ?? 0) * 20 }}%"></div>
                                        </div>
                                        <span class="badge bg-warning text-dark">{{ $calificaciones['precio'] ?? 0 }}/5</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Promedio General:</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                            <div class="progress-bar bg-primary" style="width: {{ ($calificaciones['promedio'] ?? 0) * 20 }}%"></div>
                                        </div>
                                        <span class="badge bg-primary">{{ $calificaciones['promedio'] ?? 0 }}/5</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Compras Recientes -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="bi bi-cart-check me-2"></i>Compras Recientes</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Concepto</th>
                                <th>Monto</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($compras as $compra)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $compra->created_at->format('d/m/Y') }}</td>
                                <td>{{ $compra->concepto ?? 'Compra general' }}</td>
                                <td><strong>S/ {{ number_format($compra->total, 2) }}</strong></td>
                                <td>
                                    @if($compra->estado === 'completada')
                                        <span class="badge bg-success">Completada</span>
                                    @elseif($compra->estado === 'pendiente')
                                        <span class="badge bg-warning">Pendiente</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $compra->estado }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">No hay compras registradas</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Documentos -->
    @if($proveedor->documentos->count() > 0)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="bi bi-file-earmark me-2"></i>Documentos Adjuntos</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($proveedor->documentos as $doc)
                        <div class="col-md-3 mb-3">
                            <div class="card text-center">
                                <div class="card-body p-2">
                                    <i class="bi bi-file-earmark-pdf" style="font-size: 2rem; color: #dc3545;"></i>
                                    <p class="small mt-2 mb-0"><strong>{{ Str::limit($doc->nombre_original, 20) }}</strong></p>
                                    <small class="text-muted d-block">{{ $doc->tipo }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Botones de Acción -->
    <div class="row">
        <div class="col-md-12 d-flex gap-2">
            <a href="{{ route('proveedor.edit', $proveedor->idProveedor) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Editar
            </a>
            <a href="{{ route('proveedor.historial', $proveedor->idProveedor) }}" class="btn btn-info">
                <i class="bi bi-clock-history me-2"></i>Historial Financiero
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
