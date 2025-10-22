@extends('layouts.plantillaPago')

@section('contenido')
    <div class="d-flex flex-column justify-content-between h-100">

        <!-- Parte superior -->
        <div>
            <h1 class="h4 mb-2 text-primary fw-bold">
                <i class="bi bi-file-earmark-text me-2"></i>
                Copias certificadas de actas/partidas
            </h1>
            <p class="text-muted mb-3">Servicio en Línea</p>

            <div class="mb-3">
                <h2 class="h6 text-dark">
                    <i class="bi bi-person-lines-fill me-2"></i> Estimado Usuario:
                </h2>
                <p class="text-muted small mb-0">
                    Para tener acceso al servicio en línea, primero debe validar sus datos.
                </p>
            </div>

            <div class="d-flex align-items-start bg-primary bg-opacity-10 border rounded p-3 mb-4">
                <div class="me-3 text-primary fs-4">
                    <i class="bi bi-patch-check"></i>
                </div>
                <div>
                    <h6 class="mb-1 text-dark">Valide sus datos</h6>
                    <small class="text-muted mb-1 d-block">Paso 1 de 5</small>
                    <p class="mb-0 text-muted small">
                        Ingrese su número de DNI, correo electrónico y fecha de nacimiento, luego presione "Iniciar".
                    </p>
                </div>
            </div>

            <!-- Formulario -->
            <form action="{{ route('pagos.datos') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <!-- DNI -->
                    <div class="col-md-6">
                        <label for="dni" class="form-label">Número de DNI</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-white">
                                <i class="bi bi-credit-card-2-front"></i>
                            </span>
                            <input type="text" class="form-control @error('dni') is-invalid @enderror" id="dni"
                                name="dni" maxlength="8" placeholder="Ej. 12345678" value="{{ old('dni') }}">
                        </div>
                        @error('dni')
                            <div class="invalid-feedback d-block">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Fecha de nacimiento -->
                    <div class="col-md-6">
                        <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-white">
                                <i class="bi bi-calendar-event"></i>
                            </span>
                            <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}">
                        </div>
                        @error('fecha_nacimiento')
                            <div class="invalid-feedback d-block">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Correo electrónico -->
                    <div class="col-12">
                        <label for="correo" class="form-label">Correo electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-white">
                                <i class="bi bi-envelope-at"></i>
                            </span>
                            <input type="email" class="form-control @error('correo') is-invalid @enderror" id="correo"
                                name="correo" placeholder="usuario@correo.com" value="{{ old('correo') }}">
                        </div>
                        @error('correo')
                            <div class="invalid-feedback d-block">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
                        <i class="bi bi-arrow-left"></i> Regresar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Iniciar <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
