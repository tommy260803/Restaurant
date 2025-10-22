@extends('layouts.plantilla')

@section('contenido')
    <div class="container py-4">
        <h3 class="mb-4">Detalle del Alcalde</h3>

        <div class="card mb-4">
            <div class="card-header">Información Personal</div>
            <div class="card-body">
                <p><strong>Nombres:</strong> {{ $alcalde->persona->nombres }} {{ $alcalde->persona->apellido_paterno }}
                    {{ $alcalde->persona->apellido_materno }}</p>
                <p><strong>DNI:</strong> {{ $alcalde->persona->dni }}</p>
                <p><strong>Fecha de Nacimiento:</strong> {{ $alcalde->persona->fecha_nacimiento }}</p>
                <p><strong>Nacionalidad:</strong> {{ $alcalde->persona->nacionalidad }}</p>
                <p><strong>Estado Civil:</strong> {{ $alcalde->persona->estado_civil }}</p>
                <p><strong>Distrito:</strong> {{ $alcalde->persona->distrito->nombre }}</p>
                <p><strong>Provincia:</strong> {{ $alcalde->persona->distrito->provincia->nombre }}</p>
                <p><strong>Región:</strong> {{ $alcalde->persona->distrito->provincia->region->nombre }}</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Mandato</div>
            <div class="card-body">
                <p><strong>Fecha de Inicio:</strong> {{ $alcalde->fecha_inicio }}</p>
                <p><strong>Fecha de Fin:</strong> {{ $alcalde->fecha_fin ?? 'No definida' }}</p>
                <p><strong>Estado:</strong> {{ $alcalde->estado ? 'Activo' : 'Inactivo' }}</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Registrado Por</div>
            <div class="card-body">
                @if ($alcalde->administrador && $alcalde->administrador->usuario && $alcalde->administrador->usuario->persona)
                    <p><strong>Nombre del Registrador:</strong> {{ $alcalde->administrador->usuario->persona->nombres }}
                        {{ $alcalde->administrador->usuario->persona->apellido_paterno }}</p>
                @else
                    <p class="text-muted">Información del registrador no disponible.</p>
                @endif
            </div>
        </div>

        <a href="{{ route('alcalde.index') }}" class="btn btn-secondary">Volver</a>
    </div>
@endsection
