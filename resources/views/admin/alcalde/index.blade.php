@extends('layouts.plantilla')

@section('contenido')
    <link rel="stylesheet" href="{{ asset('css/alcalde.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">

    <div class="container py-4">
        <!-- Header con información del sistema -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">
                            <i class="fas fa-university me-2 text-primary"></i>
                            Registro de Alcaldes Municipales
                        </h4>
                        <p class="text-muted mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Sistema de Registro Civil - Gestión de Autoridades Municipales
                        </p>
                    </div>

                    <!-- Botón de registro con validación -->
                    @php
                        $alcaldeActivo = $alcaldes
                            ->where('estado', 1)
                            ->where('fecha_fin', '>=', now()->format('Y-m-d'))
                            ->first();
                    @endphp

                    @if ($alcaldeActivo)
                        <div class="text-end">
                            <button class="btn btn-outline-secondary" disabled title="Ya existe un alcalde activo">
                                <i class="ri-user-add-line me-1"></i> Registrar Nuevo Alcalde
                            </button>
                            <small class="d-block text-muted mt-1">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Alcalde activo vigente
                            </small>
                        </div>
                    @else
                        <a href="{{ route('alcalde.create') }}" class="btn btn-primary">
                            <i class="ri-user-add-line me-1"></i> Registrar Nuevo Alcalde
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Estadísticas generales -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-user-check fa-2x mb-2"></i>
                        <h5 class="card-title">{{ $alcaldes->where('estado', 1)->count() }}</h5>
                        <p class="card-text mb-0">Alcalde(s) Activo(s)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-user-clock fa-2x mb-2"></i>
                        <h5 class="card-title">{{ $alcaldes->where('estado', 0)->count() }}</h5>
                        <p class="card-text mb-0">Alcalde(s) Inactivo(s)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-history fa-2x mb-2"></i>
                        <h5 class="card-title">{{ $alcaldes->where('fecha_fin', '<', now()->format('Y-m-d'))->count() }}
                        </h5>
                        <p class="card-text mb-0">Mandato(s) Vencido(s)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <h5 class="card-title">{{ $alcaldes->count() }}</h5>
                        <p class="card-text mb-0">Total Registros</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros y ordenamiento -->
        <div class="card shadow-sm rounded-4 mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-filter me-2"></i>Filtros de Búsqueda
                </h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('alcalde.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="1" {{ request('estado') == '1' ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ request('estado') == '0' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ordenar por</label>
                        <select name="ordenar" class="form-select">
                            <option value="fecha_inicio_desc"
                                {{ request('ordenar') == 'fecha_inicio_desc' ? 'selected' : '' }}>
                                Fecha Inicio (Más reciente)
                            </option>
                            <option value="fecha_inicio_asc"
                                {{ request('ordenar') == 'fecha_inicio_asc' ? 'selected' : '' }}>
                                Fecha Inicio (Más antiguo)
                            </option>
                            <option value="nombre_asc" {{ request('ordenar') == 'nombre_asc' ? 'selected' : '' }}>
                                Nombre (A-Z)
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Buscar por nombre o DNI</label>
                        <input type="text" name="buscar" class="form-control" placeholder="Ingrese nombre o DNI"
                            value="{{ request('buscar') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-search me-1"></i>Buscar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de alcaldes -->
        @forelse ($alcaldes as $alcalde)
            @php
                $estaActivo = $alcalde->estado == 1;
                $mandatoVigente = now()->between($alcalde->fecha_inicio, $alcalde->fecha_fin);
                $mandatoVencido = now()->gt($alcalde->fecha_fin);
                $diasRestantes = $mandatoVigente ? now()->diffInDays($alcalde->fecha_fin, false) : 0;
            @endphp

            <div
                class="card shadow-sm rounded-4 mb-4 {{ $estaActivo && $mandatoVigente ? 'border-success' : ($mandatoVencido ? 'border-danger' : 'border-warning') }}">
                <div class="row g-0">
                    <!-- Foto del alcalde -->
                    <div class="col-md-3 p-3 text-center position-relative">
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $alcalde->foto) }}" alt="Foto del Alcalde"
                                class="img-fluid rounded-circle border {{ $estaActivo && $mandatoVigente ? 'border-success' : 'border-secondary' }}"
                                style="max-width: 150px;">

                            <!-- Badge de estado -->
                            @if ($estaActivo && $mandatoVigente)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                    <i class="fas fa-check"></i>
                                </span>
                            @elseif($mandatoVencido)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <i class="fas fa-times"></i>
                                </span>
                            @else
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                                    <i class="fas fa-pause"></i>
                                </span>
                            @endif
                        </div>

                        <!-- Estado del mandato -->
                        <div class="mt-2">
                            @if ($estaActivo && $mandatoVigente)
                                <span class="badge bg-success">
                                    <i class="fas fa-crown me-1"></i>ALCALDE ACTUAL
                                </span>
                            @elseif($mandatoVencido)
                                <span class="badge bg-danger">
                                    <i class="fas fa-history me-1"></i>MANDATO VENCIDO
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-pause me-1"></i>INACTIVO
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title mb-1 text-primary">
                                        {{ $alcalde->persona->nombres ?? 'N/A' }}
                                        {{ $alcalde->persona->apellido_paterno ?? '' }}
                                        {{ $alcalde->persona->apellido_materno ?? '' }}
                                    </h5>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-id-card me-1"></i>
                                        DNI: {{ $alcalde->persona->dni ?? 'N/A' }}
                                    </p>
                                </div>

                                <div class="text-end">
                                    @if (!$mandatoVencido)
                                        <a href="{{ route('alcalde.edit', $alcalde->id_alcalde) }}"
                                            class="btn btn-success btn-sm">
                                            <i class="ri-edit-line"></i> Editar
                                        </a>
                                    @endif
                                    <a href="{{ route('alcalde.show', $alcalde->id_alcalde) }}"
                                        class="btn btn-info btn-sm">
                                        <i class="ri-eye-line"></i> Ver Detalles
                                    </a>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Información Personal</h6>
                                    <p class="mb-1">
                                        <strong>Fecha de Nacimiento:</strong>
                                        {{ $alcalde->persona->fecha_nacimiento ? \Carbon\Carbon::parse($alcalde->persona->fecha_nacimiento)->format('d/m/Y') : 'N/A' }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Distrito:</strong>
                                        {{ $alcalde->persona->distrito->nombre ?? 'N/A' }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Provincia:</strong>
                                        {{ $alcalde->persona->distrito->provincia->nombre ?? 'N/A' }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Región:</strong>
                                        {{ $alcalde->persona->distrito->provincia->region->nombre ?? 'N/A' }}
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="text-muted">Información del Mandato</h6>
                                    <p class="mb-1">
                                        <strong>Inicio de Gestión:</strong>
                                        {{ \Carbon\Carbon::parse($alcalde->fecha_inicio)->format('d/m/Y') }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Fin de Gestión:</strong>
                                        {{ \Carbon\Carbon::parse($alcalde->fecha_fin)->format('d/m/Y') }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Duración del Mandato:</strong>
                                        {{ \Carbon\Carbon::parse($alcalde->fecha_inicio)->diffInYears($alcalde->fecha_fin) }}
                                        años
                                    </p>

                                    @if ($mandatoVigente && $estaActivo)
                                        <p class="mb-1 text-success">
                                            <strong>Días Restantes:</strong>
                                            {{ $diasRestantes }} días
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <hr class="my-3">

                            <div class="row">
                                <div class="col-md-6">
                                    <strong class="text-muted">Registrado por:</strong>
                                    @if ($alcalde->administrador && $alcalde->administrador->usuario && $alcalde->administrador->usuario->persona)
                                        <p class="mb-1">
                                            <i class="fas fa-user-shield me-1"></i>
                                            {{ optional($alcalde->administrador->usuario->persona)->nombres ?? 'N/A' }}
                                            {{ optional($alcalde->administrador->usuario->persona)->apellido_paterno ?? '' }}
                                            {{ optional($alcalde->administrador->usuario->persona)->apellido_materno ?? '' }}
                                        </p>
                                    @else
                                        <p class="mb-1 text-muted">
                                            <i class="fas fa-user-times me-1"></i>
                                            Administrador no disponible
                                        </p>
                                    @endif
                                </div>

                                <div class="col-md-6 text-end">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        Registrado:
                                        {{ \Carbon\Carbon::parse($alcalde->created_at ?? $alcalde->fecha_inicio)->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card shadow-sm rounded-4">
                <div class="card-body text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay alcaldes registrados</h5>
                    <p class="text-muted">
                        No se encontraron registros de alcaldes en el sistema.
                    </p>
                    @if (!$alcaldeActivo)
                        <a href="{{ route('alcalde.create') }}" class="btn btn-primary mt-3">
                            <i class="ri-user-add-line me-1"></i> Registrar Primer Alcalde
                        </a>
                    @endif
                </div>
            </div>
        @endforelse

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $alcaldes->links() }}
        </div>

    </div>

    <style>
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
        }

        .border-success {
            border-left: 4px solid #28a745 !important;
        }

        .border-danger {
            border-left: 4px solid #dc3545 !important;
        }

        .border-warning {
            border-left: 4px solid #ffc107 !important;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
        }

        .img-fluid {
            transition: all 0.3s ease;
        }

        .card:hover .img-fluid {
            transform: scale(1.05);
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
@endsection
