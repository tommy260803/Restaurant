@extends('layouts.plantilla')
@section('title', 'Nueva Mesa')

@section('contenido')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-plus-square"></i> Nueva Mesa</h1>
        <a href="{{ route('mesas.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('mesas.store') }}" method="POST" class="row g-3">
                @csrf

                <div class="col-md-4">
                    <label class="form-label">Número</label>
                    <input type="number" name="numero" class="form-control" min="1" required value="{{ old('numero') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Capacidad</label>
                    <input type="number" name="capacidad" class="form-control" min="1" max="12" required value="{{ old('capacidad', 2) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select" required>
                        <option value="disponible" {{ old('estado','disponible')==='disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="reservada" {{ old('estado')==='reservada' ? 'selected' : '' }}>Reservada</option>
                        <option value="ocupada" {{ old('estado')==='ocupada' ? 'selected' : '' }}>Ocupada</option>
                        <option value="mantenimiento" {{ old('estado')==='mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                    </select>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary"><i class="bi bi-save"></i> Guardar</button>
                    <a href="{{ route('mesas.index') }}" class="btn btn-light">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
