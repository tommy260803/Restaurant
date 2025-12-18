@extends('layouts.plantilla')

@section('titulo', 'Nuevo Personal del Restaurante')
@section('styles')
    <link rel="stylesheet" href="css/persona.css">
@endsection
@section('contenido')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <div class="container">
        <h4 class="text-primary mb-3"><i class="fas fa-user-plus me-2"></i>Registrar Personal del Restaurante</h4>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Corrige los errores:</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('persona.store') }}" method="POST" autocomplete="off">
            @csrf
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <h5 class="card-title mb-0 text-center bg-primary py-2 text-white">
                            <i class="bi bi-person-fill me-2"></i>Información Personal
                        </h5>
                        <div class="card-body">
                            
                            {{-- DNI --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="dni" class="form-label">DNI <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('dni') is-invalid @enderror" 
                                        id="dni" name="dni" value="{{ old('dni') }}" 
                                        maxlength="8" required pattern="\d{8}" placeholder="12345678">
                                    @error('dni')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <i class="fas fa-magic text-info me-1"></i> Auto-completa nombres y apellidos
                                    </small>
                                </div>
                            </div>

                            {{-- Nombres y Apellidos --}}
                            <div class="mb-3">
                                <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombres') is-invalid @enderror" 
                                    id="nombres" name="nombres" value="{{ old('nombres') }}" 
                                    required placeholder="Se completa automáticamente" readonly>
                                @error('nombres')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="apellido_paterno" class="form-label">Apellido Paterno <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('apellido_paterno') is-invalid @enderror"
                                        id="apellido_paterno" name="apellido_paterno" value="{{ old('apellido_paterno') }}"
                                        required placeholder="Se completa automáticamente" readonly>
                                    @error('apellido_paterno')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="apellido_materno" class="form-label">Apellido Materno <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('apellido_materno') is-invalid @enderror"
                                        id="apellido_materno" name="apellido_materno" value="{{ old('apellido_materno') }}"
                                        required placeholder="Se completa automáticamente" readonly>
                                    @error('apellido_materno')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Sexo --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="sexo" class="form-label">Sexo <span class="text-danger">*</span></label>
                                    <select class="form-select @error('sexo') is-invalid @enderror" id="sexo" name="sexo" required>
                                        <option value="">Seleccione...</option>
                                        <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                    @error('sexo')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Dirección --}}
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control @error('direccion') is-invalid @enderror"
                                    id="direccion" name="direccion" value="{{ old('direccion') }}"
                                    placeholder="Ej: Av. Principal 123">
                                @error('direccion')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Botones de Acción --}}
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-success flex-fill">
                                    <i class="bi bi-save me-1"></i>Registrar Personal
                                </button>
                                <a href="{{ route('cancelarp') }}" class="btn btn-secondary flex-fill">
                                    <i class="bi bi-arrow-left me-1"></i>Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Columna de Información --}}
                <div class="col-md-4">
                    <div class="card">
                        <h5 class="card-title mb-0 text-center bg-info py-2 text-white">
                            <i class="bi bi-info-circle me-2"></i>Instrucciones
                        </h5>
                        <div class="card-body">
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-lightbulb me-2"></i>
                                <strong>Pasos:</strong>
                                <ol class="mb-0 mt-2">
                                    <li>Ingrese DNI de 8 dígitos</li>
                                    <li>Los nombres y apellidos se auto-completarán</li>
                                    <li>Seleccione el sexo</li>
                                    <li>Agregue dirección si es necesario</li>
                                    <li>Guarde el registro</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const elements = {
                dniInput: document.getElementById('dni'),
                nombresInput: document.getElementById('nombres'),
                apellidoPaternoInput: document.getElementById('apellido_paterno'),
                apellidoMaternoInput: document.getElementById('apellido_materno'),
            };

            // Consulta DNI al perder el foco
            elements.dniInput.addEventListener('blur', function() {
                const dni = this.value;

                if (dni.length === 8) {
                    elements.nombresInput.placeholder = 'Consultando...';
                    elements.apellidoPaternoInput.placeholder = 'Consultando...';
                    elements.apellidoMaternoInput.placeholder = 'Consultando...';

                    fetch(`/persona/consultar-dni/${dni}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                elements.nombresInput.placeholder = 'Se completa automáticamente';
                                elements.apellidoPaternoInput.placeholder = 'Se completa automáticamente';
                                elements.apellidoMaternoInput.placeholder = 'Se completa automáticamente';
                            } else {
                                elements.nombresInput.value = data.nombres || '';
                                elements.apellidoPaternoInput.value = data.apellidoPaterno || '';
                                elements.apellidoMaternoInput.value = data.apellidoMaterno || '';
                            }
                        })
                        .catch(error => {
                            elements.nombresInput.placeholder = 'Se completa automáticamente';
                            elements.apellidoPaternoInput.placeholder = 'Se completa automáticamente';
                            elements.apellidoMaternoInput.placeholder = 'Se completa automáticamente';
                        });
                }
            });

            // Limpiar datos si DNI es inválido
            elements.dniInput.addEventListener('input', function() {
                if (this.value.length === 0) {
                    elements.nombresInput.value = '';
                    elements.apellidoPaternoInput.value = '';
                    elements.apellidoMaternoInput.value = '';
                }
            });
        });
    </script>
@endsection