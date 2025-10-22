@extends('admin.usuario.perfil')

@section('datosUsuario')
    <div class="card-body p-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('usuarios.cuenta.update', $usuario->id_usuario) }}" method="POST"
            enctype="multipart/form-data" id="perfilForm">
            @csrf
            @method('PUT')
            <h4 class="mb-4 fw-bold text-primary">Mi Cuenta</h4>

            <div class="row g-3">
                <h6 class="text-success border-bottom pb-2 mb-3">
                    <i class="fas fa-file-alt me-2"></i> I. Datos Personales
                </h6>

                <div class="col-md-6">
                    <label class="form-label">Apellido Paterno</label>
                    <input type="text" name="apellido_paterno" class="form-control bg-light"
                        value="{{ $usuario->persona->apellido_paterno }}" readonly>
                    <small class="text-muted">Este campo no se puede modificar por ser un dato registral.</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Apellido Materno</label>
                    <input type="text" name="apellido_materno" class="form-control bg-light"
                        value="{{ $usuario->persona->apellido_materno }}" readonly>
                    <small class="text-muted">Este campo no se puede modificar por ser un dato registral.</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nombres</label>
                    <input type="text" name="nombres" class="form-control bg-light"
                        value="{{ $usuario->persona->nombres }}" readonly>
                    <small class="text-muted">Este campo no se puede modificar por ser un dato registral.</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Sexo</label>
                    <input type="text" class="form-control bg-light"
                        value="{{ $usuario->persona->sexo == 'M' ? 'Masculino' : 'Femenino' }}" readonly>
                    <small class="text-muted">Este campo no se puede modificar por ser un dato registral.</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">DNI</label>
                    <input type="text" name="dni" class="form-control bg-light" value="{{ $usuario->persona->dni }}"
                        readonly>
                    <small class="text-muted">Este campo no se puede modificar por ser el documento de identidad.</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="form-control bg-light"
                        value="{{ $usuario->persona->fecha_nacimiento }}" readonly>
                    <small class="text-muted">Este campo no se puede modificar por ser un dato registral.</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email de Contacto</label>
                    <input type="email" name="email_respaldo"
                        class="form-control @error('email_respaldo') is-invalid @enderror"
                        value="{{ old('email_respaldo', $usuario->email_respaldo) }}" placeholder="ejemplo@correo.com">
                    @error('email_respaldo')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                    <small class="text-muted">Este email se usará para notificaciones y recuperación de cuenta.</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Estado Civil</label>
                    <select name="estado_civil" class="form-select @error('estado_civil') is-invalid @enderror">
                        <option value="">Seleccione...</option>
                        <option value="Soltero"
                            {{ old('estado_civil', $usuario->persona->estado_civil) == 'Soltero' ? 'selected' : '' }}>
                            Soltero</option>
                        <option value="Casado"
                            {{ old('estado_civil', $usuario->persona->estado_civil) == 'Casado' ? 'selected' : '' }}>
                            Casado</option>
                        <option value="Divorciado"
                            {{ old('estado_civil', $usuario->persona->estado_civil) == 'Divorciado' ? 'selected' : '' }}>
                            Divorciado</option>
                        <option value="Viudo"
                            {{ old('estado_civil', $usuario->persona->estado_civil) == 'Viudo' ? 'selected' : '' }}>
                            Viudo</option>
                    </select>
                    @error('estado_civil')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <h6 class="text-danger border-bottom pb-2 mb-3">
                    <i class="fas fa-map-marker-alt me-2"></i> II. Datos de Ubicación Actual
                </h6>

                <div class="col-6 mb-3">
                    <label class="form-label">Nacionalidad</label>
                    <input type="text" class="form-control bg-light" name="nacionalidad"
                        value="{{ $usuario->persona->nacionalidad }}" readonly>
                    <small class="text-muted">Este campo no se puede modificar por ser un dato registral.</small>
                </div>

                <div class="col-6 mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                        value="{{ old('telefono', $usuario->persona->telefono ?? '') }}" placeholder="987654321">
                    @error('telefono')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                    <small class="text-muted">Número de contacto para notificaciones.</small>
                </div>

                <div class="col-6 mb-3">
                    <label class="form-label">Región (Domicilio Actual)</label>
                    <select class="form-control @error('id_region') is-invalid @enderror" id="id_region" name="id_region">
                        <option value="">Seleccione una Región</option>
                        @foreach ($regiones as $region)
                            <option value="{{ $region->id_region }}"
                                {{ old('id_region', $usuario->persona->distrito->provincia->region->id_region ?? '') == $region->id_region ? 'selected' : '' }}>
                                {{ $region->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_region')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                    <small class="text-muted">Seleccione su región de domicilio actual.</small>
                </div>

                <div class="col-6 mb-3">
                    <label class="form-label">Provincia (Domicilio Actual)</label>
                    <select class="form-control @error('id_provincia') is-invalid @enderror" id="id_provincia"
                        name="id_provincia">
                        <option value="">Seleccione una Provincia</option>
                        @foreach ($provincias as $provincia)
                            @if ($provincia->id_region == old('id_region', $usuario->persona->distrito->provincia->region->id_region ?? null))
                                <option value="{{ $provincia->id_provincia }}"
                                    {{ old('id_provincia', $usuario->persona->distrito->provincia->id_provincia ?? '') == $provincia->id_provincia ? 'selected' : '' }}>
                                    {{ $provincia->nombre }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('id_provincia')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="col-6 mb-3">
                    <label class="form-label">Distrito (Domicilio Actual)</label>
                    <select class="form-control @error('id_distrito') is-invalid @enderror" id="id_distrito"
                        name="id_distrito">
                        <option value="">Seleccione un Distrito</option>
                        @foreach ($distritos as $distrito)
                            @if ($distrito->id_provincia == old('id_provincia', $usuario->persona->distrito->provincia->id_provincia ?? null))
                                <option value="{{ $distrito->id_distrito }}"
                                    {{ old('id_distrito', $usuario->persona->id_distrito ?? '') == $distrito->id_distrito ? 'selected' : '' }}>
                                    {{ $distrito->nombre }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('id_distrito')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Dirección Completa (Domicilio Actual)</label>
                    <textarea name="direccion" class="form-control @error('direccion') is-invalid @enderror" rows="2"
                        placeholder="Av. Los Pinos 123, Urbanización Los Robles, etc.">{{ old('direccion', $usuario->persona->direccion ?? '') }}</textarea>
                    @error('direccion')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                    <small class="text-muted">Ingrese su dirección completa actual.</small>
                </div>
            </div>

            <div class="text-center mt-5">
                <button type="submit" class="btn btn-primary px-4" id="btnActualizar">
                    <i class="ri-save-2-line me-2"></i>Actualizar Perfil
                </button>
            </div>
        </form>
    </div>
    <script src="/js/ubicacion.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('perfilForm');
            const btnActualizar = document.getElementById('btnActualizar');


            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                btnActualizar.disabled = true;
                btnActualizar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Actualizando...';

                const formData = new FormData(form);

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const result = await response.json();

                    if (response.ok) {
                        showSuccessMessage(result.message || 'Perfil actualizado correctamente');

                        clearErrors();
                    } else {
                        if (result.errors) {
                            showValidationErrors(result.errors);
                        } else {
                            showErrorMessage(result.message || 'Error al actualizar el perfil');
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showErrorMessage('Error de conexión. Intente nuevamente.');
                } finally {
                    btnActualizar.disabled = false;
                    btnActualizar.innerHTML = '<i class="ri-save-2-line me-2"></i>Actualizar Perfil';
                }
            });

            function showSuccessMessage(message) {
                const alertContainer = document.querySelector('.card-body');
                const existingAlert = alertContainer.querySelector('.alert');

                if (existingAlert) {
                    existingAlert.remove();
                }

                const successAlert = document.createElement('div');
                successAlert.className = 'alert alert-success alert-dismissible fade show';
                successAlert.innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                alertContainer.insertBefore(successAlert, alertContainer.firstChild);

                // Scroll al inicio para ver el mensaje
                alertContainer.scrollIntoView({
                    behavior: 'smooth'
                });
            }

            function showErrorMessage(message) {
                const alertContainer = document.querySelector('.card-body');
                const existingAlert = alertContainer.querySelector('.alert');

                if (existingAlert) {
                    existingAlert.remove();
                }

                const errorAlert = document.createElement('div');
                errorAlert.className = 'alert alert-danger alert-dismissible fade show';
                errorAlert.innerHTML = `
                    <i class="fas fa-exclamation-circle me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                alertContainer.insertBefore(errorAlert, alertContainer.firstChild);
                alertContainer.scrollIntoView({
                    behavior: 'smooth'
                });
            }

            function showValidationErrors(errors) {
                // Limpiar errores anteriores
                clearErrors();

                Object.keys(errors).forEach(field => {
                    const fieldElement = document.querySelector(`[name="${field}"]`);
                    if (fieldElement) {
                        fieldElement.classList.add('is-invalid');

                        // Crear o actualizar mensaje de error
                        let errorElement = fieldElement.parentNode.querySelector('.invalid-feedback');
                        if (!errorElement) {
                            errorElement = document.createElement('span');
                            errorElement.className = 'invalid-feedback';
                            fieldElement.parentNode.appendChild(errorElement);
                        }
                        errorElement.innerHTML = `<strong>${errors[field][0]}</strong>`;
                    }
                });
            }

            function clearErrors() {
                const invalidFields = document.querySelectorAll('.is-invalid');
                invalidFields.forEach(field => {
                    field.classList.remove('is-invalid');
                });

                const errorMessages = document.querySelectorAll('.invalid-feedback');
                errorMessages.forEach(error => {
                    if (!error.querySelector('strong')) {
                        error.remove();
                    }
                });
            }
        });
    </script>
@endsection
