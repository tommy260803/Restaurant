@extends('layouts.plantilla')

@section('titulo', 'Nuevo registro de Persona')
@section('styles')
    <link rel="stylesheet" href="css/persona.css">
@endsection
@section('contenido')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <div class="container">
        <h4 class="text-primary mb-3"><i class="fas fa-user-plus me-2"></i>Registro de Nueva Persona</h4>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Corrige los errores:</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('persona.store') }}" method="POST" autocomplete="off">
            @csrf
            <input type="hidden" name="estado" value="1">
            
            <div class="row">
                <!-- Columna Izquierda: Datos de Identificación -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <h5 class="card-title mb-0 text-center bg-primary py-2 text-white">👤 Datos de Identificación</h5>
                        <div class="card-body" style="height:70vh;overflow-y:auto">
                            
                            {{-- Datos de Identificación --}}
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-2 fs-5">
                                <i class="bi bi-id-card me-2"></i>Información Personal
                            </h6>
                            <div class="row mb-2">
                                <div class="col-12 mb-2">
                                    <label for="dni" class="mb-2">DNI <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('dni') is-invalid @enderror" id="dni"
                                        name="dni" value="{{ old('dni') }}" maxlength="8" required pattern="\d{8}"
                                        placeholder="12345678">
                                    @error('dni')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                    <span id="dni-error-message" class="invalid-feedback" role="alert" style="display: none;">
                                        <strong id="dni-error-text"></strong>
                                    </span>
                                    <div class="form-text text-muted">
                                        <i class="fas fa-magic text-info me-1"></i> Auto-completa nombres y apellidos
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="fecha_nacimiento" class="mb-2">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                            id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                                            required>
                                        <span class="input-group-text bg-dark text-white" style="pointer-events: none;">
                                            <i class="bi bi-calendar-event"></i>
                                        </span>
                                    </div>
                                    @error('fecha_nacimiento')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Datos Personales --}}
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-3 fs-5">
                                <i class="bi bi-person-fill me-2"></i>Nombres y Apellidos
                            </h6>
                            <div class="mb-2">
                                <label for="nombres" class="mb-2">Nombres <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombres') is-invalid @enderror" id="nombres"
                                    name="nombres" value="{{ old('nombres') }}" required placeholder="Se completa automáticamente" readonly>
                                @error('nombres')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="row mb-2">
                                <div class="col-6 mb-2">
                                    <label for="apellido_paterno" class="mb-2">Apellido Paterno <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('apellido_paterno') is-invalid @enderror"
                                        id="apellido_paterno" name="apellido_paterno" value="{{ old('apellido_paterno') }}"
                                        required placeholder="Se completa automáticamente" readonly>
                                    @error('apellido_paterno')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-6 mb-2">
                                    <label for="apellido_materno" class="mb-2">Apellido Materno <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('apellido_materno') is-invalid @enderror"
                                        id="apellido_materno" name="apellido_materno" value="{{ old('apellido_materno') }}"
                                        required placeholder="Se completa automáticamente" readonly>
                                    @error('apellido_materno')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Información Adicional --}}
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-3 fs-5">
                                <i class="bi bi-info-circle me-2"></i>Información Adicional
                            </h6>
                            <div class="row mb-2">
                                <div class="col-4 mb-2">
                                    <label for="sexo" class="mb-2">Sexo <span class="text-danger">*</span></label>
                                    <select class="form-select @error('sexo') is-invalid @enderror" id="sexo" name="sexo" required>
                                        <option value="">Seleccione...</option>
                                        <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                                        <option value="O" {{ old('sexo') == 'O' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                    @error('sexo')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-4 mb-2">
                                    <label for="estado_civil" class="mb-2">Estado Civil</label>
                                    <select class="form-select @error('estado_civil') is-invalid @enderror" id="estado_civil" name="estado_civil">
                                        <option value="">Seleccione...</option>
                                        <option value="Soltero" {{ old('estado_civil') == 'Soltero' ? 'selected' : '' }}>Soltero</option>
                                        <option value="Casado" {{ old('estado_civil') == 'Casado' ? 'selected' : '' }}>Casado</option>
                                        <option value="Divorciado" {{ old('estado_civil') == 'Divorciado' ? 'selected' : '' }}>Divorciado</option>
                                        <option value="Viudo" {{ old('estado_civil') == 'Viudo' ? 'selected' : '' }}>Viudo</option>
                                    </select>
                                    @error('estado_civil')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-4 mb-2">
                                    <label for="nacionalidad" class="mb-2">Nacionalidad</label>
                                    <input type="text" class="form-control @error('nacionalidad') is-invalid @enderror"
                                        id="nacionalidad" name="nacionalidad" value="{{ old('nacionalidad') }}"
                                        placeholder="Ej: Peruana">
                                    @error('nacionalidad')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-success flex-fill">
                                    <i class="bi bi-save me-1"></i>Registrar Persona
                                </button>
                                <a href="{{ route('cancelarp') }}" class="btn btn-secondary flex-fill">
                                    <i class="bi bi-arrow-left me-1"></i>Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Ubicación Geográfica -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <h5 class="card-title mb-0 text-center bg-dark py-2 text-white">🌍 Ubicación Geográfica</h5>
                        <div class="card-body" style="height:70vh;overflow-y:auto">
                            
                            {{-- Ubicación --}}
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-3 fs-5">
                                <i class="bi bi-geo-alt-fill me-2"></i>Lugar de Residencia
                            </h6>
                            <div class="row mb-2">
                                <div class="col-12 mb-2">
                                    <label for="id_region" class="mb-2">Departamento <span class="text-danger">*</span></label>
                                    <select class="form-select @error('id_region') is-invalid @enderror" id="id_region" name="id_region" required>
                                        <option value="">Seleccione departamento</option>
                                        @foreach ($regiones as $region)
                                            <option value="{{ $region->id_region }}"
                                                {{ old('id_region') == $region->id_region ? 'selected' : '' }}>
                                                {{ $region->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_region')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="id_provincia" class="mb-2">Provincia <span class="text-danger">*</span></label>
                                    <select class="form-select @error('id_provincia') is-invalid @enderror" id="id_provincia" name="id_provincia" required disabled>
                                        <option value="">Seleccione provincia</option>
                                    </select>
                                    @error('id_provincia')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="id_distrito" class="mb-2">Distrito <span class="text-danger">*</span></label>
                                    <select class="form-select @error('id_distrito') is-invalid @enderror" id="id_distrito" name="id_distrito" required disabled>
                                        <option value="">Seleccione distrito</option>
                                    </select>
                                    @error('id_distrito')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Información del Sistema --}}
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-3 fs-5">
                                <i class="bi bi-info-square me-2"></i>Información del Sistema
                            </h6>
                            <div class="alert alert-info">
                                <i class="bi bi-lightbulb me-2"></i>
                                <strong>Instrucciones:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Complete el DNI de 8 dígitos para autocompletar nombres</li>
                                    <li>Todos los campos marcados con (*) son obligatorios</li>
                                    <li>Seleccione la ubicación geográfica en orden: Departamento → Provincia → Distrito</li>
                                    <li>Verifique que todos los datos sean correctos antes de guardar</li>
                                </ul>
                            </div>

                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Nota:</strong> Los datos de nombres y apellidos se obtienen automáticamente del DNI. Si no se encuentran, deberá ingresarlos manualmente.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="/js/ubicacion.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos del DOM
            const elements = {
                dniInput: document.getElementById('dni'),
                nombresInput: document.getElementById('nombres'),
                apellidoPaternoInput: document.getElementById('apellido_paterno'),
                apellidoMaternoInput: document.getElementById('apellido_materno'),
                errorMessage: document.getElementById('dni-error-message'),
                errorText: document.getElementById('dni-error-text')
            };

            // Función para mostrar errores del DNI
            function showDniError(message) {
                elements.dniInput.classList.add('is-invalid');
                elements.errorText.textContent = message;
                elements.errorMessage.style.display = 'block';
            }

            // Función para limpiar errores del DNI
            function clearDniError() {
                elements.dniInput.classList.remove('is-invalid');
                elements.errorMessage.style.display = 'none';
            }

            // Función para limpiar los datos personales
            function clearPersonalData() {
                elements.nombresInput.value = '';
                elements.apellidoPaternoInput.value = '';
                elements.apellidoMaternoInput.value = '';
            }

            // Consulta DNI al perder el foco
            elements.dniInput.addEventListener('blur', function() {
                const dni = this.value;
                clearDniError();

                if (dni.length === 8) {
                    // Mostrar indicador de carga
                    elements.nombresInput.placeholder = 'Consultando...';
                    elements.apellidoPaternoInput.placeholder = 'Consultando...';
                    elements.apellidoMaternoInput.placeholder = 'Consultando...';

                    fetch(`/persona/consultar-dni/${dni}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                showDniError('DNI no encontrado');
                                clearPersonalData();
                                // Restaurar placeholders
                                elements.nombresInput.placeholder = 'Se completa automáticamente';
                                elements.apellidoPaternoInput.placeholder = 'Se completa automáticamente';
                                elements.apellidoMaternoInput.placeholder = 'Se completa automáticamente';
                            } else {
                                // Llenar los campos con los datos obtenidos
                                elements.nombresInput.value = data.nombres || '';
                                elements.apellidoPaternoInput.value = data.apellidoPaterno || '';
                                elements.apellidoMaternoInput.value = data.apellidoMaterno || '';
                            }
                        })
                        .catch(error => {
                            showDniError('DNI no encontrado');
                            clearPersonalData();
                            // Restaurar placeholders
                            elements.nombresInput.placeholder = 'Se completa automáticamente';
                            elements.apellidoPaternoInput.placeholder = 'Se completa automáticamente';
                            elements.apellidoMaternoInput.placeholder = 'Se completa automáticamente';
                        });
                } else if (dni.length > 0) {
                    showDniError('El DNI debe tener exactamente 8 dígitos.');
                    clearPersonalData();
                }
            });

            // Validar mientras se escribe
            elements.dniInput.addEventListener('input', function() {
                const dni = this.value;

                if (dni.length < 8) {
                    clearDniError();
                }

                if (dni.length === 0) {
                    clearPersonalData();
                }
            });

            // Restaurar valores old() para ubicación si existen
            @if(old('id_region'))
                setTimeout(() => {
                    const regionSelect = document.getElementById('id_region');
                    const provinciaSelect = document.getElementById('id_provincia');
                    const distritoSelect = document.getElementById('id_distrito');
                    
                    regionSelect.value = '{{ old('id_region') }}';
                    regionSelect.dispatchEvent(new Event('change'));
                    
                    setTimeout(() => {
                        @if(old('id_provincia'))
                            provinciaSelect.value = '{{ old('id_provincia') }}';
                            provinciaSelect.dispatchEvent(new Event('change'));
                            setTimeout(() => {
                                @if(old('id_distrito'))
                                    distritoSelect.value = '{{ old('id_distrito') }}';
                                @endif
                            }, 300);
                        @endif
                    }, 300);
                }, 100);
            @endif
        });
    </script>
@endsection