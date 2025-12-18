@extends('layouts.plantilla')

@section('contenido')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card shadow rounded-4 border-0">
                <div class="card-header bg-gradient-primary text-white rounded-top-4">
                    <h4 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-user-plus me-2"></i> Registro de Nuevo Usuario
                    </h4>
                </div>
                <div class="card-body bg-white">
                    <form method="POST" action="{{ route('usuarios.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-4">
                            <!-- Persona -->
                            <div class="col-md-6">
                                <label for="dni_usuario" class="form-label fw-semibold">
                                    <i class="fas fa-id-card text-primary me-1"></i> DNI de la Persona
                                </label>
                                <select class="form-select @error('dni_usuario') is-invalid @enderror" name="dni_usuario" required>
                                    <option value="">Seleccione un DNI</option>
                                    @foreach($personas as $persona)
                                        <option value="{{ $persona->dni }}" {{ old('dni_usuario') == $persona->dni ? 'selected' : '' }}>
                                            {{ $persona->dni }} - {{ $persona->nombres }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('dni_usuario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nombre de usuario -->
                            <div class="col-md-6">
                                <label for="nombre_usuario" class="form-label fw-semibold">
                                    <i class="fas fa-user text-primary me-1"></i> Nombre de Usuario
                                </label>
                                <input type="text" name="nombre_usuario" class="form-control @error('nombre_usuario') is-invalid @enderror" value="{{ old('nombre_usuario') }}" placeholder="Ej: jdoe123" required>
                                @error('nombre_usuario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contraseña -->
                            <div class="col-md-6">
                                <label for="contrasena" class="form-label fw-semibold">
                                    <i class="fas fa-lock text-primary me-1"></i> Contraseña
                                </label>
                                <input type="password" name="contrasena" class="form-control @error('contrasena') is-invalid @enderror" placeholder="Ingrese una contraseña" required>
                                @error('contrasena')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Rol -->
                            <div class="col-md-6">
                                <label for="rol" class="form-label fw-semibold">
                                    <i class="fas fa-user-tag text-primary me-1"></i> Rol
                                </label>
                                <select name="rol" class="form-select @error('rol') is-invalid @enderror" required>
                                    <option value="">Seleccione un rol</option>
                                    <option value="administrador" {{ old('rol') == 'administrador' ? 'selected' : '' }}>Administrador</option>
                                    <option value="cocinero" {{ old('rol') == 'cocinero' ? 'selected' : '' }}>Cocinero</option>
                                    <option value="almacenero" {{ old('rol') == 'almacenero' ? 'selected' : '' }}>Almacenero</option>
                                    <option value="cajero" {{ old('rol') == 'cajero' ? 'selected' : '' }}>Cajero</option>
                                    <option value="mesero" {{ old('rol') == 'mesero' ? 'selected' : '' }}>Mesero</option>
                                    <option value="registrador" {{ old('rol') == 'registrador' ? 'selected' : '' }}>Registrador</option>
                                </select>
                                @error('rol')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Correo Principal -->
                            <div class="col-md-6">
                                <label for="email_mi_acta" class="form-label fw-semibold">
                                    <i class="fas fa-envelope text-primary me-1"></i> Correo Principal
                                </label>
                                <input type="email" name="email_mi_acta" class="form-control @error('email_mi_acta') is-invalid @enderror" value="{{ old('email_mi_acta') }}" placeholder="ejemplo@correo.com" required>
                                @error('email_mi_acta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Correo de Respaldo -->
                            <div class="col-md-6">
                                <label for="email_respaldo" class="form-label fw-semibold">
                                    <i class="fas fa-envelope-open text-primary me-1"></i> Correo de Respaldo
                                </label>
                                <input type="email" name="email_respaldo" class="form-control @error('email_respaldo') is-invalid @enderror" value="{{ old('email_respaldo') }}" placeholder="opcional@correo.com">
                                @error('email_respaldo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-4">
                            <a href="{{ route('cancelaru') }}" class="btn btn-outline-danger">
                                <i class="fas fa-ban me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Guardar Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        });
    })();
</script>

@endsection
