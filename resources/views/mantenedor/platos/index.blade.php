@extends('layouts.plantilla')

@section('titulo', 'Gestión de Platos')

@section('contenido')
<div class="container mt-4">
    <h1 class="mb-4 text-center text-md-start">Gestión de Platos</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <!-- Botón Nuevo Plato y contador -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
        <a href="{{ route('mantenedor.platos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Plato
        </a>

        @if(request('buscar') || request('categoria'))
            <span class="badge bg-info">
                {{ $platos->total() }} resultado(s) encontrado(s)
            </span>
        @endif
    </div>

    <!-- Barra de búsqueda -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('mantenedor.platos.index') }}" class="row g-3">
                        <div class="col-12 col-md-4">
                            <label for="buscar" class="form-label">Buscar por nombre:</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="buscar" 
                                   name="buscar" 
                                   value="{{ request('buscar') }}" 
                                   placeholder="Ingrese nombre del plato...">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="categoria" class="form-label">Filtrar por categoría:</label>
                            <select class="form-select" id="categoria" name="categoria">
                                <option value="">Todas las categorías</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->idCategoria }}" 
                                            {{ request('categoria') == $categoria->idCategoria ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-4 d-flex flex-column flex-md-row align-items-stretch align-items-md-end gap-2">
                            <button type="submit" class="btn btn-outline-primary w-100 w-md-auto">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                            <a href="{{ route('mantenedor.platos.index') }}" class="btn btn-outline-secondary w-100 w-md-auto">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de platos -->
    <div class="table-responsive-sm">
        <table class="table table-striped table-hover shadow-sm mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="text-center align-middle">#</th>
                    <th class="text-center align-middle">Imagen</th>
                    <th class="text-start align-middle">Nombre</th>
                    <th class="text-start align-middle">Descripción</th>
                    <th class="text-start align-middle">Categoría</th>
                    <th class="text-end align-middle">Precio</th>
                    <th class="text-center align-middle">Disponibilidad</th>
                    <th class="text-center align-middle">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($platos as $plato)
                    <tr>
                        <td class="text-center align-middle">{{ $plato->idPlatoProducto }}</td>

                        <td class="text-center align-middle">
                            @if($plato->imagen)
                                <img src="{{ asset('storage/' . $plato->imagen) }}"
                                    alt="Imagen de {{ $plato->nombre }}"
                                    width="60"
                                    class="img-thumbnail imagen-ampliable"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalImagen"
                                    data-imagen="{{ asset('storage/' . $plato->imagen) }}"
                                    data-nombre="{{ $plato->nombre }}">
                            @else
                                <span class="text-muted">Sin imagen</span>
                            @endif
                        </td>

                        <td class="text-start align-middle">{{ $plato->nombre }}</td>

                        <td class="text-start align-middle text-truncate" style="max-width: 240px;">
                            @if($plato->descripcion)
                                {{ Str::limit($plato->descripcion, 50) }}
                            @else
                                <span class="text-muted">Sin descripción</span>
                            @endif
                        </td>

                        <td class="text-start align-middle">{{ $plato->categoria->nombre ?? 'Sin categoría' }}</td>

                        <td class="text-end align-middle">S/. {{ number_format($plato->precio, 2) }}</td>

                        <!-- Disponibilidad: centrado vertical y horizontal -->
                        <td class="text-center align-middle">
                            @if($plato->disponible)
                                <span class="badge bg-success">Disponible</span>
                            @else
                                <span class="badge bg-danger">Agotado</span>
                            @endif
                        </td>

                        <!-- Acciones: centrado y responsive (apila en xs, fila en md+) -->
                        <td class="text-center align-middle" style="min-width:160px;">
                            <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-2">
                                <a href="{{ route('mantenedor.platos.edit', $plato->idPlatoProducto) }}" 
                                class="btn btn-outline-primary btn-sm w-100 w-md-auto">
                                    <i class="fas fa-edit me-1"></i> Editar
                                </a>

                                <a href="{{ route('mantenedor.platos.confirmar', $plato->idPlatoProducto) }}" 
                                class="btn btn-outline-danger btn-sm w-100 w-md-auto">
                                    <i class="fas fa-trash-alt me-1"></i> Eliminar
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-utensils fa-3x mb-3"></i>
                                <h5>No se encontraron platos</h5>
                                @if(request('buscar') || request('categoria'))
                                    <p>Intenta con otros criterios de búsqueda</p>
                                    <a href="{{ route('mantenedor.platos.index') }}" class="btn btn-outline-primary">
                                        Ver todos los platos
                                    </a>
                                @else
                                    <p>Comienza agregando tu primer plato</p>
                                    <a href="{{ route('mantenedor.platos.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> Crear Plato
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-3">
        {{ $platos->appends(request()->query())->links() }}
    </div>
</div>

<!-- Modal Imagen -->
<div class="modal fade" id="modalImagen" tabindex="-1" aria-labelledby="modalImagenLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalImagenLabel">Imagen del Plato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="imagenAmpliada" src="" alt="" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imagenesAmpliables = document.querySelectorAll('.imagen-ampliable');
    const imagenAmpliada = document.getElementById('imagenAmpliada');
    const modalTitulo = document.getElementById('modalImagenLabel');
    
    imagenesAmpliables.forEach(function(imagen) {
        imagen.addEventListener('click', function() {
            imagenAmpliada.src = this.dataset.imagen;
            imagenAmpliada.alt = 'Imagen de ' + this.dataset.nombre;
            modalTitulo.textContent = this.dataset.nombre;
        });
    });
});
</script>


<!-- Estilos extra para alinear badges y botones -->
@push('styles')
<style>
/* Forzar alineación vertical en toda la tabla */
.table thead th,
.table tbody td {
    vertical-align: middle !important;
}

/* Evitar que los botones hagan wrap y mantener apariencia consistente */
.table .btn {
    white-space: nowrap;
}

/* Badge line-height y padding para que quede centrado */
.table .badge {
    line-height: 1.2;
    padding: .35em .6em;
}

/* Ajuste del ancho mínimo del área de acciones para evitar salto de columnas */
@media (min-width: 768px) {
    .table td[style*="min-width:160px"] { min-width: 160px; }
}

/* En pantallas muy pequeñas, reducir ancho de descripción */
@media (max-width: 575.98px) {
    .table .text-truncate { max-width: 140px; }
}
</style>
@endpush

@endsection
