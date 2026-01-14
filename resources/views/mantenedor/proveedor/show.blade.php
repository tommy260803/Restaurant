@extends('layouts.plantilla')

@section('contenido')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h3 class="text-primary"><i class="bi bi-person-vcard me-2"></i>Detalles del Proveedor</h3>
        </div>
    </div>

    <!-- Información Principal -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ $proveedor->nombre }} {{ $proveedor->apellidoPaterno }} {{ $proveedor->apellidoMaterno }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Tipo de Persona</h6>
                            <p class="lead">
                                @if($proveedor->tipo_persona === 'natural')
                                    <span class="badge bg-info">Persona Natural</span>
                                @else
                                    <span class="badge bg-primary">Persona Jurídica</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Estado</h6>
                            <p class="lead">
                                @if($proveedor->estado === 'activo')
                                    <span class="badge bg-success" style="font-size: 0.9rem; padding: 6px 12px;">✓ Activo</span>
                                @elseif($proveedor->estado === 'bloqueado')
                                    <span class="badge bg-danger" style="font-size: 0.9rem; padding: 6px 12px;">✗ Bloqueado</span>
                                @else
                                    <span class="badge bg-secondary" style="font-size: 0.9rem; padding: 6px 12px;">○ Inactivo</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1"><i class="bi bi-envelope me-1"></i>Email</h6>
                            <p>{{ $proveedor->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1"><i class="bi bi-telephone me-1"></i>Teléfono</h6>
                            <p>{{ $proveedor->telefono }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1"><i class="bi bi-credit-card me-1"></i>RUC</h6>
                            <p>{{ $proveedor->rucProveedor ?: 'No especificado' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1"><i class="bi bi-geo-alt me-1"></i>Dirección</h6>
                            <p>{{ $proveedor->direccion ?: 'No especificada' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="text-muted mb-1">Calificación General</h6>
                            <h4 class="text-warning">
                                {{ $proveedor->calificacion > 0 ? $proveedor->calificacion . '/5 ⭐' : 'Sin calificar' }}
                            </h4>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted mb-1">Incumplimientos</h6>
                            <h4 class="text-danger">{{ $proveedor->incumplimientos }}</h4>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted mb-1">Registrado desde</h6>
                            <p>{{ $proveedor->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Lateral de Estadísticas -->
        <div class="col-md-4">
            <div class="card mb-3 border-left-primary">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-3">Calificaciones Detalladas</h6>
                    <div class="mb-3">
                        <small class="text-muted">Puntualidad</small>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ ($proveedor->puntualidad ?? 0) * 20 }}%"></div>
                        </div>
                        <small class="text-muted">{{ $proveedor->puntualidad ?? 0 }}/5</small>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Calidad</small>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: {{ ($proveedor->calidad ?? 0) * 20 }}%"></div>
                        </div>
                        <small class="text-muted">{{ $proveedor->calidad ?? 0 }}/5</small>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Precio</small>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: {{ ($proveedor->precio ?? 0) * 20 }}%"></div>
                        </div>
                        <small class="text-muted">{{ $proveedor->precio ?? 0 }}/5</small>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="bi bi-file-earmark me-2"></i>Documentos</h6>
                </div>
                <div class="card-body">
                    @if($proveedor->documentos->count() > 0)
                        <p class="text-muted mb-3">
                            <small>{{ $proveedor->documentos->count() }} documento(s) adjunto(s)</small>
                        </p>
                        <div class="list-group list-group-sm">
                            @foreach($proveedor->documentos as $doc)
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-pdf text-danger me-2"></i>
                                    <span class="small">{{ Str::limit($doc->nombre_original, 25) }}</span>
                                </div>
                                <span class="badge bg-light text-dark">{{ $doc->tipo }}</span>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3 mb-0">
                            <i class="bi bi-inbox" style="font-size: 2rem; color: #ccc;"></i>
                            <p>No hay documentos</p>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Compras Recientes -->
    @if($proveedor->compras->count() > 0)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-cart-check me-2"></i>Últimas Compras</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
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
                            @php $contador = 1; @endphp
                            @foreach($proveedor->compras->take(5) as $compra)
                            <tr>
                                <td>{{ $contador++ }}</td>
                                <td>{{ $compra->created_at->format('d/m/Y') }}</td>
                                <td>{{ $compra->concepto ?? 'Compra general' }}</td>
                                <td><strong>S/ {{ number_format($compra->total, 2) }}</strong></td>
                                <td>
                                    @if($compra->estado === 'completada')
                                        <span class="badge bg-success">Completada</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($compra->estado ?? 'Pendiente') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('proveedor.historial', $proveedor->idProveedor) }}" class="btn btn-sm btn-outline-primary">
                        Ver Historial Completo
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Botones de Acción -->
    <div class="row mb-4">
        <div class="col-md-12 d-flex gap-2 flex-wrap">
            <a href="{{ route('proveedor.edit', $proveedor->idProveedor) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Editar
            </a>
            <a href="{{ route('proveedor.dashboard', $proveedor->idProveedor) }}" class="btn btn-info">
                <i class="bi bi-graph-up me-2"></i>Dashboard
            </a>
            <a href="{{ route('proveedor.historial', $proveedor->idProveedor) }}" class="btn btn-outline-info">
                <i class="bi bi-clock-history me-2"></i>Historial
            </a>
            @if($proveedor->estado !== 'activo')
                <form action="{{ route('proveedor.activar', $proveedor->idProveedor) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-2"></i>Activar
                    </button>
                </form>
            @endif
            <a href="{{ route('proveedor.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
</div>

<style>
    .border-left-primary { border-left: 4px solid #0066cc !important; }
</style>

@endsection
