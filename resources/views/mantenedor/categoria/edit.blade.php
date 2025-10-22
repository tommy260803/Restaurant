@extends('layouts.plantilla')

@section('titulo', 'Editar Categoría')

@section('contenido')

<div class="container py-4">
<h3 class="fw-bold text-primary mb-3">
<i class="fas fa-edit me-2"></i>Editar Categoría
</h3>

<div class="card shadow-lg border-0">
    <div class="card-body">
        {{-- CORRECCIÓN A: route('mantenedor.categorias.update') --}}
        <form action="{{ route('mantenedor.categorias.update', $categoria->idCategoria) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nombre" class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                <input type="text" name="nombre" id="nombre"
                       class="form-control @error('nombre') is-invalid @enderror"
                       value="{{ old('nombre', $categoria->nombre) }}" required>
                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label fw-semibold">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="3"
                          class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label fw-semibold">Estado</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="activo" {{ old('estado', $categoria->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estado', $categoria->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                {{-- CORRECCIÓN A: route('mantenedor.categorias.index') --}}
                <a href="{{ route('mantenedor.categorias.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Actualizar
                </button>
            </div>
        </form>
    </div>
</div>

</div>
@endsection