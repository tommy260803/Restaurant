{{-- filepath: resources/views/compras/create.blade.php --}}
@extends('layouts.plantilla')
@section('contenido')
<div class="container mt-4">
    <h1 class="mb-4">Nueva Compra</h1>
    <form method="POST" action="{{ route('compras.store') }}" id="formCompra">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="idProveedor" class="form-label">Proveedor <span class="text-danger">*</span></label>
                <select name="idProveedor" id="idProveedor" class="form-select @error('idProveedor') is-invalid @enderror" required>
                    <option value="">-- Seleccione un proveedor --</option>
                    @foreach($proveedores as $proveedor)
                        <option value="{{ $proveedor->idProveedor }}" {{ old('idProveedor') == $proveedor->idProveedor ? 'selected' : '' }}>
                            {{ $proveedor->nombre }} {{ $proveedor->apellidoPaterno }} - RUC: {{ $proveedor->rucProveedor ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
                @error('idProveedor')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3 mb-3">
                <label for="fecha" class="form-label">Fecha <span class="text-danger">*</span></label>
                <input type="date" name="fecha" id="fecha" class="form-control @error('fecha') is-invalid @enderror" value="{{ old('fecha', date('Y-m-d')) }}" required>
                @error('fecha')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3 mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en_transito" {{ old('estado') == 'en_transito' ? 'selected' : '' }}>En Tránsito</option>
                    <option value="recibida" {{ old('estado') == 'recibida' ? 'selected' : '' }}>Recibida</option>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
            <textarea name="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3" required>{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-1"></i> Guardar Compra
            </button>
            <a href="{{ route('compras.index') }}" class="btn btn-secondary">
                <i class="fas fa-times me-1"></i> Cancelar
            </a>
        </div>
    </form>

    <div class="alert alert-info mt-3">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Nota:</strong> Después de guardar la compra, podrás agregar los detalles de ingredientes en la pantalla de edición.
    </div>
</div>
@endsection