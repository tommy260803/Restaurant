@extends('layouts.plantilla')
@section('contenido')
    <div class="container">
        <h2>Detalle del Permiso</h2>
        <p><strong>ID:</strong> {{ $permiso->id }}</p>
        <p><strong>Nombre:</strong> {{ $permiso->name }}</p> <a href="{{ route('permisos.index') }}"
            class="btn btn-secondary">Volver</a>
    </div>
@endsection
