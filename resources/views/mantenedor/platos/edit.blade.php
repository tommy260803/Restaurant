@extends('layouts.plantilla')

@section('titulo', 'Editar Plato')

@section('contenido')
<div class="container mt-4">
    <h1 class="mb-4">Editar Plato</h1>

    {{-- Debug: Mostrar el ID del plato (puedes comentar esta línea después) --}}
    {{-- <div class="alert alert-info">ID del plato: {{ $plato->idPlatoProducto ?? 'No encontrado' }}</div> --}}

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- CORRECCIÓN: Asegurar que se use el ID correcto --}}
    <form action="{{ route('mantenedor.platos.update', $plato->idPlatoProducto ?? $plato->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Plato</label>
            <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $plato->nombre) }}" required>
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3">{{ old('descripcion', $plato->descripcion) }}</textarea>
            @error('descripcion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio (S/.)</label>
            <input type="number" step="0.01" name="precio" id="precio" class="form-control @error('precio') is-invalid @enderror" value="{{ old('precio', $plato->precio) }}" required>
            @error('precio')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="idCategoria" class="form-label">Categoría</label>
            <select name="idCategoria" id="idCategoria" class="form-select @error('idCategoria') is-invalid @enderror" required>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->idCategoria }}" 
                        {{ (old('idCategoria', $plato->idCategoria ?? '')) == $categoria->idCategoria ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
            @error('idCategoria')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen del Plato</label>
            <input type="file" name="imagen" id="imagen" class="form-control @error('imagen') is-invalid @enderror" accept="image/*">
            @if($plato->imagen)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $plato->imagen) }}" alt="Imagen actual" width="120" class="img-thumbnail">
                </div>
            @endif
            @error('imagen')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="disponible" id="disponible" class="form-check-input" value="1" {{ old('disponible', $plato->disponible) ? 'checked' : '' }}>
            <label for="disponible" class="form-check-label">Disponible</label>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('mantenedor.platos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection