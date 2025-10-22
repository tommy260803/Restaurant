@extends('layouts.plantilla')

@section('contenido')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card shadow rounded-4 border-0">
                    <div class="card-header bg-gradient-primary text-black rounded-top-4">
                        <h4 class="mb-0 d-flex align-items-center">
                            <i class="fas fa-user-tie me-2"></i> Registrar Nuevo Alcalde
                        </h4>
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
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('alcalde.store') }}" enctype="multipart/form-data"
                            class="needs-validation" novalidate>

                            @csrf

                            <div class="row g-4">
                                <!-- Persona -->
                                <div class="col-md-6">
                                    <label for="dni_alcalde" class="form-label fw-semibold">
                                        <i class="fas fa-id-card text-primary me-1"></i> Persona
                                    </label>
                                    <select name="dni_alcalde" id="dni_alcalde"
                                        class="form-select @error('dni_alcalde') is-invalid @enderror" required>
                                        <option value="">Seleccione una persona</option>
                                        @foreach ($personas as $persona)
                                            <option value="{{ $persona->dni }}"
                                                {{ old('dni_alcalde') == $persona->dni ? 'selected' : '' }}>
                                                {{ $persona->dni }} - {{ $persona->nombres }}
                                                {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dni_alcalde')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        <div class="invalid-feedback">
                                            Por favor seleccione una persona.
                                        </div>
                                    @enderror

                                </div>

                                <!-- Usuario Administrador (Solo lectura si es el usuario actual) -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-user-shield text-primary me-1"></i> Registrado por
                                    </label>

                                    <input type="text" class="form-control bg-light" readonly
                                        value="{{ optional(optional(auth()->user()->persona))->nombres .
                                            ' ' .
                                            optional(auth()->user()->persona)->apellido_paterno .
                                            ' ' .
                                            optional(auth()->user()->persona)->apellido_materno ??
                                            'Usuario sin nombre' }}">

                                    @error('id_administrador')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="fecha_inicio" class="form-label fw-semibold">
                                        <i class="fas fa-calendar-alt text-primary me-1"></i> Fecha de Inicio
                                    </label>
                                    <input type="date" name="fecha_inicio" class="form-control"
                                        value="{{ old('fecha_inicio', now()->format('Y-m-d')) }}" readonly>

                                    @error('fecha_inicio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        La fecha de inicio se establece automáticamente como la fecha actual.
                                    </small>
                                </div>

                                <!-- Fecha de Fin (Calculada automáticamente) -->
                                <div class="col-md-6">
                                    <label for="fecha_fin" class="form-label fw-semibold">
                                        <i class="fas fa-calendar-check text-primary me-1"></i> Fecha de Fin
                                    </label>
                                    <input type="date" name="fecha_fin" class="form-control"
                                        value="{{ old('fecha_fin', now()->addYears(5)->format('Y-m-d')) }}" readonly>

                                    @error('fecha_fin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        El periodo de mandato es de 5 años (calculado automáticamente).
                                    </small>
                                </div>

                                <!-- Estado (Por defecto Activo) -->
                                <div class="col-md-6">
                                    <label for="estado" class="form-label fw-semibold">
                                        <i class="fas fa-toggle-on text-primary me-1"></i> Estado
                                    </label>
                                    <select name="estado" id="estado"
                                        class="form-select @error('estado') is-invalid @enderror" required>
                                        <option value="1" {{ old('estado', '1') == '1' ? 'selected' : '' }}>
                                            <i class="fas fa-check-circle text-success"></i> Activo
                                        </option>
                                        <option value="0" {{ old('estado') == '0' ? 'selected' : '' }}>
                                            <i class="fas fa-times-circle text-danger"></i> Inactivo
                                        </option>
                                    </select>
                                    @error('estado')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Los nuevos alcaldes se registran como "Activo" por defecto.
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="foto" class="form-label fw-semibold">
                                        <i class="fas fa-image text-primary me-1"></i> Foto de perfil
                                    </label>
                                    <input type="file" name="foto" id="foto"
                                        class="form-control @error('foto') is-invalid @enderror" accept="image/*" required>
                                    @error('foto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Información adicional -->
                                <div class="col-12">
                                    <div class="alert alert-info border-0 rounded-3">
                                        <h6 class="alert-heading">
                                            <i class="fas fa-info-circle me-2"></i>Información del Registro
                                        </h6>
                                        <ul class="mb-0 small">
                                            <li>El periodo de mandato es de <strong>5 años</strong> a partir de la fecha de
                                                inicio.</li>
                                            <li>La fecha de inicio se establece automáticamente como la fecha actual del
                                                sistema.</li>
                                            <li>Solo se pueden registrar personas que no tengan un mandato activo vigente.
                                            </li>
                                            <li>El estado "Activo" permite al alcalde ejercer sus funciones.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-4">
                                <a href="{{ route('alcalde.index') }}" class="btn btn-outline-danger">
                                    <i class="fas fa-ban me-1"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success" id="submitBtn">
                                    <i class="fas fa-save me-1"></i> Guardar Alcalde
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

            // Validación del formulario
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });

            // Actualizar fechas automáticamente
            const fechaInicio = document.getElementById('fecha_inicio');
            const fechaFin = document.getElementById('fecha_fin');

            function actualizarFechas() {
                const fechaInicioDate = new Date(fechaInicio.value);
                if (fechaInicioDate instanceof Date && !isNaN(fechaInicioDate)) {
                    const fechaFinDate = new Date(fechaInicioDate);
                    fechaFinDate.setFullYear(fechaFinDate.getFullYear() + 5);
                    fechaFin.value = fechaFinDate.toISOString().split('T')[0];
                }
            }

            // Escuchar cambios en fecha de inicio (por si se modifica programáticamente)
            fechaInicio.addEventListener('change', actualizarFechas);

            // Validación personalizada para DNI
            const dniSelect = document.getElementById('dni_alcalde');
            dniSelect.addEventListener('change', function() {
                if (this.value === '') {
                    this.setCustomValidity('Por favor seleccione una persona');
                } else {
                    this.setCustomValidity('');
                }
            });

            // Confirmar envío del formulario
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.addEventListener('click', function(e) {
                const form = document.querySelector('.needs-validation');
                if (form.checkValidity()) {
                    // Opcional: Mostrar loading state
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Guardando...';
                    this.disabled = true;

                    // Reactivar el botón después de 5 segundos por seguridad
                    setTimeout(() => {
                        this.innerHTML = '<i class="fas fa-save me-1"></i> Guardar Alcalde';
                        this.disabled = false;
                    }, 5000);
                }
            });

            // Inicializar fechas al cargar la página
            actualizarFechas();
        })();
    </script>

    <style>
        .alert-info {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
        }

        .form-control[readonly] {
            background-color: #f8f9fa;
            opacity: 0.8;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .invalid-feedback {
            display: block;
        }

        .was-validated .form-select:invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'><circle cx='6' cy='6' r='4.5'/><path d='m5.8 5.8 1.4 1.4M7.2 5.8 5.8 7.2'/></svg>");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
    </style>
@endsection
