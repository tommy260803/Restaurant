@extends('layouts.plantilla')
@section('title', 'Editar Personal')
@section('contenido')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0"><i class="fas fa-user-edit me-2"></i> Editar Personal del Restaurante</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('persona.update', $persona->id_persona) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="dni" class="form-label">DNI <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"
                                    id="dni" name="dni" value="{{ old('dni', $persona->dni) }}" 
                                    disabled title="El DNI no puede ser modificado">
                                <small class="text-muted">El DNI no se puede cambiar</small>
                            </div>

                            <div class="mb-3">
                                <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombres') is-invalid @enderror"
                                    id="nombres" name="nombres" value="{{ old('nombres', $persona->nombres) }}" required>
                                @error('nombres')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="apellido_paterno" class="form-label">Apellido Paterno <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('apellido_paterno') is-invalid @enderror"
                                        id="apellido_paterno" name="apellido_paterno"
                                        value="{{ old('apellido_paterno', $persona->apellido_paterno) }}" required>
                                    @error('apellido_paterno')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="apellido_materno" class="form-label">Apellido Materno <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('apellido_materno') is-invalid @enderror"
                                        id="apellido_materno" name="apellido_materno"
                                        value="{{ old('apellido_materno', $persona->apellido_materno) }}" required>
                                    @error('apellido_materno')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                            <div class="mb-3">
                                <label for="sexo" class="form-label">Sexo <span class="text-danger">*</span></label>
                                <select class="form-select @error('sexo') is-invalid @enderror"
                                        id="sexo" name="sexo" required>
                                        <option value="">Seleccione...</option>
                                        <option value="M" {{ old('sexo', $persona->sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ old('sexo', $persona->sexo) == 'F' ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                    @error('sexo')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                            </div>

                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control @error('direccion') is-invalid @enderror"
                                    id="direccion" name="direccion" 
                                    value="{{ old('direccion', $persona->direccion) }}"
                                    placeholder="Ej: Av. Principal 123">
                                @error('direccion')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <hr class="my-4">
                            <div class="d-flex justify-content-between">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success flex-fill">
                                    <i class="fas fa-save me-1"></i> Guardar Cambios
                                </button>
                                <a href="{{ route('persona.index') }}" class="btn btn-secondary flex-fill">
                                    <i class="fas fa-times me-1"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
