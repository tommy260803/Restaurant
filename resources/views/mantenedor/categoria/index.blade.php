@extends('layouts.plantilla')

@section('titulo', 'Gestión de Categorías')

@section('contenido')
<div class="container py-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h2 class="fw-bold text-primary mb-0">
            <i class="fas fa-layer-group me-2"></i>Gestión de Categorías
        </h2>
        <a href="{{ route('mantenedor.categorias.create') }}" class="btn btn-success shadow-sm">
            <i class="fas fa-plus-circle me-1"></i> Nueva Categoría
        </a>
    </div>

    <!-- Contadores -->
    <div class="alert alert-info shadow-sm d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 p-3">
        <div class="d-flex align-items-center">
            <i class="fas fa-list me-2 text-primary"></i>
            <span class="text-secondary fw-medium me-2">Total de categorías:</span>
            <strong class="text-dark">{{ $categorias->total() }}</strong>
        </div>

        <div class="vr d-none d-md-block mx-3"></div>

        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2 text-success"></i>
            <span class="text-secondary fw-medium me-2">Categorías Activas:</span>
            <strong class="text-dark">{{ $totalActivas }}</strong>
        </div>
    </div>

    <!-- Barra de búsqueda -->
    <form action="{{ route('mantenedor.categorias.index') }}" method="GET" class="row g-2 mb-4">
        <div class="col-12 col-md-5">
            <input type="text" name="search" value="{{ request('search') }}" 
                class="form-control shadow-sm" placeholder="Buscar por nombre...">
        </div>
        <div class="col-12 col-md-3">
            <select name="estado" class="form-select shadow-sm">
                <option value="">-- Filtrar por estado --</option>
                <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activos</option>
                <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivos</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <button type="submit" class="btn btn-primary w-100 shadow-sm">
                <i class="fas fa-search me-1"></i> Buscar
            </button>
        </div>
        <div class="col-6 col-md-2">
            <a href="{{ route('mantenedor.categorias.index') }}" class="btn btn-secondary w-100 shadow-sm">
                <i class="fas fa-eraser me-1"></i> Limpiar
            </a>
        </div>
    </form>


    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card shadow-lg border-0">
        <div class="card-body">
            <div class="table-responsive-sm">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center align-middle">ID</th>
                            <th class="text-start align-middle">Nombre</th>
                            <th class="text-start align-middle">Descripción</th>
                            <th class="text-center align-middle">Estado</th>
                            <th class="text-center align-middle">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categorias as $categoria)
                            <tr>
                                <td class="text-center align-middle">{{ $categoria->idCategoria }}</td>

                                <td class="text-start align-middle fw-semibold text-primary">
                                    {{ $categoria->nombre }}
                                </td>

                                <td class="text-start align-middle text-truncate" style="max-width: 380px;">
                                    {{ $categoria->descripcion ?? 'Sin descripción' }}
                                </td>

                                <!-- ESTADO: centrado horizontal y vertical -->
                                <td class="text-center align-middle">
                                    <span class="badge bg-{{ $categoria->estado == 'activo' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($categoria->estado) }}
                                    </span>
                                </td>

                                <!-- ACCIONES: centrado y responsive (apila en xs, fila en md+) -->
                                <td class="text-center align-middle">
                                    <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center gap-2">
                                        <a href="{{ route('mantenedor.categorias.edit', $categoria->idCategoria) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-edit me-1"></i> Editar
                                        </a>
                                        <a href="{{ route('mantenedor.categorias.confirmar', $categoria->idCategoria) }}" 
                                           class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash-alt me-1"></i> Eliminar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-folder-open fa-2x mb-2"></i><br>
                                    No hay categorías registradas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-3">
                {{ $categorias->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Estilos extra para alinear badges y botones -->
@push('styles')
<style>
/* Asegurar alineación vertical */
.table thead th,
.table tbody td {
    vertical-align: middle !important;
}

/* Limitar ancho de descripción y truncar */
.table .text-truncate {
    max-width: 380px;
}

/* Evitar que los botones salten línea y dar tamaño consistente */
.table .btn {
    white-space: nowrap;
}

/* Ajuste del badge para no romper la alineación */
.table .badge {
    line-height: 1.2;
    padding: .35em .6em;
}

/* Opcional: pequeño ajuste para tamaños muy pequeños */
@media (max-width: 575.98px) {
    .table .text-truncate { max-width: 220px; }
}
</style>
@endpush

@endsection
