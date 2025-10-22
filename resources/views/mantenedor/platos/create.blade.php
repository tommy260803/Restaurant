@extends('layouts.plantilla')

@section('titulo', 'Registrar Plato')

@section('contenido')
<div class="container mt-4">
    <h1 class="mb-4">Registrar Plato</h1>

    {{-- CORRECCIÓN: Se cambia 'platos.store' a 'mantenedor.platos.store' --}}
    <form action="{{ route('mantenedor.platos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Plato</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio (S/.)</label>
            <input type="number" step="0.01" name="precio" id="precio" class="form-control" value="{{ old('precio') }}" required>
        </div>

        <div class="mb-3">
            <label for="idCategoria" class="form-label">Categoría</label>
            <select name="idCategoria" id="idCategoria" class="form-select" required>
                <option value="">-- Seleccione una categoría --</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->idCategoria }}">
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>


        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen del Plato</label>
            <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*">
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="disponible" id="disponible" class="form-check-input" value="1" checked>
            <label for="disponible" class="form-check-label">Disponible</label>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        {{-- CORRECCIÓN: Se cambia 'platos.index' a 'mantenedor.platos.index' --}}
        <a href="{{ route('mantenedor.platos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
