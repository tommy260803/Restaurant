@extends('layouts.plantilla')

@section('contenido')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h3 class="text-primary"><i class="bi bi-bar-chart me-2"></i>Reporte General de Proveedores</h3>
            <small class="text-muted">Resumen y análisis del módulo de proveedores</small>
        </div>
    </div>

    <!-- Estadísticas Generales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary h-100">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-1">Total de Proveedores</h6>
                    <h2 class="card-text text-primary">{{ $estadisticas['total'] }}</h2>
                    <small class="text-info">En el sistema</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-success h-100">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-1">Proveedores Activos</h6>
                    <h2 class="card-text text-success">{{ $estadisticas['activos'] }}</h2>
                    <small class="text-success">
                        @php $porcentaje = $estadisticas['total'] > 0 ? round($estadisticas['activos'] / $estadisticas['total'] * 100) : 0; @endphp
                        {{ $porcentaje }}% del total
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-warning h-100">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-1">Proveedores Inactivos</h6>
                    <h2 class="card-text text-warning">{{ $estadisticas['inactivos'] }}</h2>
                    <small class="text-warning">
                        @php $porcentaje = $estadisticas['total'] > 0 ? round($estadisticas['inactivos'] / $estadisticas['total'] * 100) : 0; @endphp
                        {{ $porcentaje }}% del total
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-danger h-100">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-1">Proveedores Bloqueados</h6>
                    <h2 class="card-text text-danger">{{ $estadisticas['bloqueados'] }}</h2>
                    <small class="text-danger">
                        @php $porcentaje = $estadisticas['total'] > 0 ? round($estadisticas['bloqueados'] / $estadisticas['total'] * 100) : 0; @endphp
                        {{ $porcentaje }}% del total
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Información Adicional -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Resumen General</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tbody>
                            <tr>
                                <td><strong>Total de Proveedores:</strong></td>
                                <td>{{ $estadisticas['total'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Activos:</strong></td>
                                <td>
                                    <span class="badge bg-success">{{ $estadisticas['activos'] }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Inactivos:</strong></td>
                                <td>
                                    <span class="badge bg-warning">{{ $estadisticas['inactivos'] }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Bloqueados:</strong></td>
                                <td>
                                    <span class="badge bg-danger">{{ $estadisticas['bloqueados'] }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Con Incumplimientos:</strong></td>
                                <td>
                                    <span class="badge bg-secondary">{{ $estadisticas['con_incumplimientos'] }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="bi bi-star me-2"></i>Proveedor Mejor Calificado</h6>
                </div>
                <div class="card-body">
                    @if($estadisticas['mejor_calificado'])
                    <div class="text-center">
                        <h5 class="card-title">{{ $estadisticas['mejor_calificado']->nombre }} {{ $estadisticas['mejor_calificado']->apellidoPaterno }}</h5>
                        <div class="mb-3">
                            <h3 class="text-warning">⭐ {{ $estadisticas['mejor_calificado']->calificacion }}/5</h3>
                        </div>
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <td><strong>Puntualidad:</strong></td>
                                    <td>{{ $estadisticas['mejor_calificado']->puntualidad }}/5</td>
                                </tr>
                                <tr>
                                    <td><strong>Calidad:</strong></td>
                                    <td>{{ $estadisticas['mejor_calificado']->calidad }}/5</td>
                                </tr>
                                <tr>
                                    <td><strong>Precio:</strong></td>
                                    <td>{{ $estadisticas['mejor_calificado']->precio }}/5</td>
                                </tr>
                            </tbody>
                        </table>
                        <a href="{{ route('proveedor.dashboard', $estadisticas['mejor_calificado']->idProveedor) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye me-1"></i>Ver Dashboard
                        </a>
                    </div>
                    @else
                    <p class="text-muted text-center py-3">No hay proveedores calificados aún</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos de Distribución -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Análisis de Distribución</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="mb-3">Estado de Proveedores</h6>
                            @php
                                $activos_pct = $estadisticas['total'] > 0 ? round($estadisticas['activos'] / $estadisticas['total'] * 100) : 0;
                                $inactivos_pct = $estadisticas['total'] > 0 ? round($estadisticas['inactivos'] / $estadisticas['total'] * 100) : 0;
                                $bloqueados_pct = $estadisticas['total'] > 0 ? round($estadisticas['bloqueados'] / $estadisticas['total'] * 100) : 0;
                            @endphp
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Activos</span>
                                    <span class="badge bg-success">{{ $activos_pct }}%</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" style="width: {{ $activos_pct }}%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Inactivos</span>
                                    <span class="badge bg-warning">{{ $inactivos_pct }}%</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-warning" style="width: {{ $inactivos_pct }}%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Bloqueados</span>
                                    <span class="badge bg-danger">{{ $bloqueados_pct }}%</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-danger" style="width: {{ $bloqueados_pct }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <h6 class="mb-3">Indicadores Clave</h6>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="bg-light p-3 rounded">
                                        <p class="text-muted mb-1">Tasa de Actividad</p>
                                        <h5 class="mb-0 text-success">
                                            @php 
                                                $tasa = $estadisticas['total'] > 0 ? round(($estadisticas['activos'] / $estadisticas['total']) * 100) : 0;
                                            @endphp
                                            {{ $tasa }}%
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="bg-light p-3 rounded">
                                        <p class="text-muted mb-1">Tasa de Bloqueo</p>
                                        <h5 class="mb-0 text-danger">
                                            @php 
                                                $tasa = $estadisticas['total'] > 0 ? round(($estadisticas['bloqueados'] / $estadisticas['total']) * 100) : 0;
                                            @endphp
                                            {{ $tasa }}%
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light p-3 rounded">
                                        <p class="text-muted mb-1">Proveedores con Incumplimientos</p>
                                        <h5 class="mb-0 text-warning">{{ $estadisticas['con_incumplimientos'] }}</h5>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light p-3 rounded">
                                        <p class="text-muted mb-1">Promedio de Incumplimientos</p>
                                        <h5 class="mb-0 text-info">
                                            @php
                                                $promedio_incumplimientos = \App\Models\Proveedor::where('incumplimientos', '>', 0)->avg('incumplimientos');
                                            @endphp
                                            {{ round($promedio_incumplimientos ?? 0, 1) }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="row mb-4">
        <div class="col-md-12 d-flex gap-2">
            <a href="{{ route('proveedor.exportarPDF') }}" class="btn btn-danger" target="_blank">
                <i class="bi bi-file-earmark-pdf me-2"></i>Exportar PDF
            </a>
            <a href="{{ route('proveedor.exportarExcel') }}" class="btn btn-success">
                <i class="bi bi-file-earmark-excel me-2"></i>Exportar Excel
            </a>
            <a href="{{ route('proveedor.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver a Proveedores
            </a>
        </div>
    </div>
</div>

<style>
    .border-left-primary { border-left: 4px solid #0066cc !important; }
    .border-left-success { border-left: 4px solid #28a745 !important; }
    .border-left-warning { border-left: 4px solid #ffc107 !important; }
    .border-left-danger { border-left: 4px solid #dc3545 !important; }
    .border-left-info { border-left: 4px solid #17a2b8 !important; }
</style>

@endsection
