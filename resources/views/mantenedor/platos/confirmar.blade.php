@extends('layouts.plantilla')

@section('titulo', 'Eliminar Plato')

@section('contenido')
    <div class="container mt-4">
        <h3 class="mb-3">¿Está seguro de eliminar este plato?</h3>

        <div class="card">
            <div class="card-body">
                <p><strong>ID:</strong> {{ $plato->idPlatoProducto }}</p>
                <p><strong>Nombre:</strong> {{ $plato->nombre }}</p>
                <p><strong>Descripción:</strong> {{ $plato->descripcion ?: 'Sin descripción' }}</p>
                <p><strong>Precio:</strong> S/. {{ number_format($plato->precio, 2) }}</p>
                <p><strong>Disponibilidad:</strong> {{ $plato->disponible ? 'Disponible' : 'No disponible' }}</p>
                <p><strong>Categoría:</strong> {{ $plato->categoria->nombre ?? 'Sin categoría' }}</p>

                <p><strong>Imagen:</strong></p>
                @if($plato->imagen)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $plato->imagen) }}"
                             alt="Imagen de {{ $plato->nombre }}"
                             width="150"
                             class="img-thumbnail">
                    </div>
                @else
                    <span class="text-muted">Sin imagen</span>
                @endif
            </div>
        </div>

        <form action="{{ route('mantenedor.platos.destroy', $plato->idPlatoProducto) }}" method="POST" class="mt-3">
            @csrf
            @method('DELETE')

            <a href="{{ route('mantenedor.platos.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
    </div>
@endsection