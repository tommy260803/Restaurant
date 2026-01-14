@extends('layouts.plantilla')

@section('contenido')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <div class="container">
        <h4 class="text-secondary mb-3"><i class="fas fa-baby me-2"></i>Editar Acta de Nacimiento</h4>

        <form action="{{ route('nacimiento.update', $acta->id_acta_nacimiento) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="card h-100">
                        <h5 class="card-title mb-0 text-center bg-success py-2 text-white">📄 Datos Registrales</h5>
                        <div class="card-body" style="height:70vh;overflow-y:auto">
                            <!-- Información del Acta -->
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-2 fs-5">
                                <i class="bi bi-file-earmark-text me-2"></i>Información del Acta
                            </h6>
                            <div class="row mb-2">
                                <div class="col-6 mb-2">
                                    <label class="mb-2">Número de Acta</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light"
                                            value="ACTA {{ str_pad($acta->id_acta_nacimiento, 5, '0', STR_PAD_LEFT) }}"
                                            readonly>
                                        <span class="input-group-text bg-dark text-white">
                                            <i class="bi bi-hash"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="mb-2">Fecha de Registro</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $acta->fecha_registro }}" readonly>
                                        <span class="input-group-text bg-dark text-white">
                                            <i class="bi bi-calendar-event"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="id_folio" class="mb-2">Folio</label>
                                    <div class="input-group">
                                        <select name="id_folio" id="id_folio"
                                            class="form-select @error('id_folio') is-invalid @enderror">
                                            <option value="">Seleccione un folio</option>
                                            @foreach ($folios as $folio)
                                                <option value="{{ $folio->id_folio }}"
                                                    {{ old('id_folio', $acta->id_folio) == $folio->id_folio ? 'selected' : '' }}>
                                                    Folio {{ $folio->numero_folio }} - Libro
                                                    {{ $folio->libro->numero_libro ?? 'N/A' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#crearFolioModal">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    @error('id_folio')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Datos del Recién Nacido -->
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-2 fs-5">
                                <i class="bi bi-file-earmark-person me-2"></i>Datos del Recién Nacido
                            </h6>
                            <div class="row mb-2">
                                <div class="mb-2">
                                    <label for="nombre_completo" class="mb-2">Nombres y Apellidos</label>
                                    <input type="text" name="nombre_completo" id="nombre_completo" class="form-control"
                                        readonly
                                        value="{{ old('nombre_completo', $acta->recienNacido->nombre . ' ' . $acta->recienNacido->apellido_paterno . ' ' . $acta->recienNacido->apellido_materno) }}">
                                    <input type="hidden" name="id_recien_nacido" id="id_recien_nacido"
                                        value="{{ old('id_recien_nacido', $acta->id_recien_nacido) }}">
                                    @error('id_recien_nacido')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="sexo" class="mb-2">Sexo</label>
                                    <div class="input-group">
                                        <select name="sexo" id="sexo"
                                            class="form-select @error('sexo') is-invalid @enderror"
                                            onchange="actualizarIconoSexo(this.value)">
                                            <option value=""
                                                {{ old('sexo', $acta->recienNacido->sexo) == '' ? 'selected' : '' }}>
                                                Seleccionar
                                            </option>
                                            <option value="M"
                                                {{ old('sexo', $acta->recienNacido->sexo) == 'M' ? 'selected' : '' }}>
                                                Masculino
                                            </option>
                                            <option value="F"
                                                {{ old('sexo', $acta->recienNacido->sexo) == 'F' ? 'selected' : '' }}>
                                                Femenino
                                            </option>
                                        </select>
                                        <span id="iconoSexo" class="input-group-text text-dark"></span>
                                    </div>
                                    @error('sexo')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="fecha_nacimiento" class="mb-2">Fecha Nacimiento</label>
                                    <div class="input-group">
                                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                                            class="form-control" required
                                            value="{{ old('fecha_nacimiento', $acta->recienNacido->fecha_nacimiento ?? '') }}">
                                        <span class="input-group-text bg-dark text-white" style="cursor: pointer;"
                                            onclick="document.getElementById('fecha_nacimiento').showPicker()">
                                            <i class="bi bi-calendar"></i>
                                        </span>
                                    </div>
                                    @error('fecha_nacimiento')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="hora_nacimiento" class="mb-2">Hora Nacimiento</label>
                                    <div class="input-group">
                                        <input type="time" name="hora_nacimiento" id="hora_nacimiento"
                                            class="form-control @error('hora_nacimiento') is-invalid @enderror"
                                            value="{{ old('hora_nacimiento', $acta->hora_nacimiento ?? '') }}">
                                        <span id="btnHora" class="input-group-text bg-dark text-white"
                                            style="cursor: pointer;">
                                            <i class="bi bi-clock"></i>
                                        </span>
                                    </div>
                                    @error('hora_nacimiento')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <style>
                                input[type="date"]::-webkit-calendar-picker-indicator,
                                input[type="time"]::-webkit-calendar-picker-indicator {
                                    display: none;
                                    -webkit-appearance: none;
                                }

                                input[type="date"],
                                input[type="time"] {
                                    position: relative;
                                    z-index: 1;
                                }
                            </style>

                            <!-- Lugar de Nacimiento -->
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-3 fs-5">
                                <i class="bi bi-geo-alt-fill me-2"></i>Lugar de Nacimiento
                            </h6>

                            <div class="mb-2">
                                <label for="direccion" class="mb-2">Dirección</label>
                                <input type="text" name="direccion" id="direccion" class="form-control" required
                                    value="{{ old('direccion', $acta->recienNacido->direccion ?? '') }}"
                                    placeholder="Ejemplo: Av. Larco 123">
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="region_nombre" class="mb-2">Región</label>
                                    <select id="region" name="region"
                                        class="form-select @error('region', 'recienNacido') is-invalid @enderror">
                                        <option value="">Seleccione región</option>
                                        @foreach ($regiones as $region)
                                            <option value="{{ $region->id_region }}"
                                                {{ old('region', $acta->recienNacido->distrito->provincia->region->id_region ?? '') == $region->id_region ? 'selected' : '' }}>
                                                {{ $region->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('region', 'recienNacido')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="region_nombre" class="mb-2">Provincia</label>
                                    <select id="provincia" name="provincia"
                                        class="form-select @error('provincia', 'recienNacido') is-invalid @enderror"
                                        {{ old('region', $acta->recienNacido->distrito->provincia->region->id_region ?? '') ? '' : 'disabled' }}>
                                        <option value="">Seleccione provincia</option>
                                        @if (old('region', $acta->recienNacido->distrito->provincia->region->id_region ?? ''))
                                            <option
                                                value="{{ old('provincia', $acta->recienNacido->distrito->provincia->id_provincia ?? '') }}"
                                                selected>
                                                {{ $acta->recienNacido->distrito->provincia->nombre ?? 'Provincia seleccionada' }}
                                            </option>
                                        @endif
                                    </select>
                                    @error('provincia', 'recienNacido')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="region_nombre" class="mb-2">Distrito</label>
                                    <select id="distrito" name="id_distrito_nac"
                                        class="form-select @error('id_distrito_nac', 'recienNacido') is-invalid @enderror"
                                        {{ old('provincia', $acta->recienNacido->distrito->provincia->id_provincia ?? '') ? '' : 'disabled' }}>
                                        <option value="">Seleccione distrito</option>
                                        @if (old('provincia', $acta->recienNacido->distrito->provincia->id_provincia ?? ''))
                                            <option
                                                value="{{ old('id_distrito_nac', $acta->recienNacido->distrito->id_distrito ?? '') }}"
                                                selected>
                                                {{ $acta->recienNacido->distrito->nombre ?? 'Distrito seleccionado' }}
                                            </option>
                                        @endif
                                    </select>
                                    @error('id_distrito_nac', 'recienNacido')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Datos del Padre -->
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-2 fs-5">
                                <i class="bi bi-gender-male me-2"></i>Datos del Padre
                            </h6>
                            <div class="row mb-2">
                                <div class="mb-2">
                                    <label for="nombre_padre" class="mb-2">Nombres y Apellidos</label>
                                    <input type="text" name="nombre_padre" id="nombre_padre"
                                        class="form-control @error('dni_padre') is-invalid @enderror"
                                        value="{{ old('nombre_padre', $acta->padre->nombres . ' ' . $acta->padre->apellido_paterno . ' ' . $acta->padre->apellido_materno ?? '') }}"
                                        readonly>
                                    <input type="hidden" name="dni_padre" id="dni_padre"
                                        value="{{ old('dni_padre', $acta->dni_padre ?? '') }}">
                                    @error('dni_padre')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Datos de la Madre -->
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-2 fs-5">
                                <i class="bi bi-gender-female me-2"></i>Datos de la Madre
                            </h6>
                            <div class="row mb-2">
                                <div class="mb-2">
                                    <label for="nombre_madre" class="mb-2">Nombres y Apellidos</label>
                                    <input type="text" name="nombre_madre" id="nombre_madre"
                                        class="form-control @error('dni_madre') is-invalid @enderror"
                                        value="{{ old('nombre_madre', $acta->madre->nombres . ' ' . $acta->madre->apellido_paterno . ' ' . $acta->madre->apellido_materno ?? '') }}"
                                        readonly>
                                    <input type="hidden" name="dni_madre" id="dni_madre"
                                        value="{{ old('dni_madre', $acta->dni_madre ?? '') }}">
                                    @error('dni_madre')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Datos del Alcalde -->
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-3 fs-5">
                                <i class="bi bi-person-badge-fill me-2"></i>Datos del Alcalde
                            </h6>
                            <div class="row mb-2">
                                <div class="mb-2">
                                    <label for="NombreAlcalde" class="mb-2">Nombres y Apellidos</label>
                                    <input type="text" id="NombreAlcalde" class="form-control bg-light" readonly
                                        required
                                        value="{{ $alcaldeVigente->persona->nombre_completo ?? 'Alcalde no disponible' }}">
                                    <input type="hidden" name="id_alcalde" id="id_alcalde"
                                        value="{{ $alcaldeVigente->id_alcalde ?? '' }}">
                                </div>
                                @error('id_alcalde')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Datos del Registrador -->
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-3 fs-5">
                                <i class="bi bi-person-vcard-fill me-2"></i>Datos del Registrador
                            </h6>
                            <div class="row mb-2">
                                <div class="mb-2">
                                    <label for="NombreUsuario" class="mb-2">Nombres y Apellidos</label>
                                    <input type="text" id="NombreUsuario" class="form-control bg-light" readonly
                                        required
                                        value="{{ $usuarioLogueado->persona->nombre_completo ?? 'Usuario no disponible' }}">
                                    <input type="hidden" name="id_usuario" id="id_usuario"
                                        value="{{ $usuarioLogueado->id_usuario ?? '' }}">
                                </div>
                                @error('id_usuario')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-success flex-fill">
                                    <i class="bi bi-save me-1"></i>Registrar Acta
                                </button>
                                <a href="{{ route('nacimiento.index') }}" class="btn btn-secondary flex-fill">
                                    <i class="bi bi-arrow-left me-1"></i>Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel Documentación -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <h5 class="card-title mb-0 text-center bg-dark py-2 text-white">📌 Documentación</h5>
                        <div class="card-body" style="height:70vh; overflow-y: auto;">
                            <div class="mb-3">
                                <label>Acta (PDF)</label>
                                <input type="file" name="ruta_archivo_pdf" class="form-control"
                                    accept="application/pdf" id="archivo_pdf">
                                <small class="text-muted">Archivo opcional. Solo PDF.</small>
                            </div>
                            <div id="visor-container"
                                style="{{ empty($acta->ruta_archivo_pdf) ? 'display: none;' : '' }}">
                                <iframe id="visor-pdf"
                                    src="{{ empty($acta->ruta_archivo_pdf) ? '' : asset('storage/' . $acta->ruta_archivo_pdf) }}"
                                    width="100%" height="400px" class="rounded border"></iframe>
                            </div>
                            <div id="info-archivo" class="alert alert-info mt-2">
                                @if (!empty($acta->ruta_archivo_pdf))
                                    <small><strong>Archivo actual:</strong> {{ basename($acta->ruta_archivo_pdf) }}</small>
                                @else
                                    <small id="mensajeDefault"><strong>No se ha seleccionado ningún
                                            archivo.</strong></small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal para crear folio -->
    <div class="modal fade" id="crearFolioModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-book me-2"></i>Crear Nuevo Folio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="libro_select">Libro</label>
                        <select id="libro_select" class="form-select" required>
                            <option value="">Seleccione un libro</option>
                            @foreach ($libros as $libro)
                                <option value="{{ $libro->id_libro }}">Libro {{ $libro->numero_libro }} -
                                    {{ $libro->tipo_libro }} ({{ $libro->anio }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Número de Folio (Automático)</label>
                        <input type="number" id="nuevo_numero_folio" class="form-control" readonly
                            placeholder="Seleccione un libro">
                        <small class="text-muted">Se genera automáticamente</small>
                    </div>
                    <div id="loading-folio" class="text-center d-none">
                        <i class="fas fa-spinner fa-spin me-2"></i>Obteniendo número...
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="confirmarCrearFolio" disabled>
                        <i class="fas fa-save me-1"></i>Crear Folio
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const token = "{{ csrf_token() }}";

            // ---------------------------
            // Actualizar icono de sexo
            // ---------------------------
            function actualizarIconoSexo(sexo) {
                const iconoSexo = document.getElementById('iconoSexo');
                const sexoInput = document.getElementById('sexo_mostrar');
                if (!iconoSexo) return;

                iconoSexo.classList.remove('d-none');

                if (sexo === 'Masculino' || sexo === 'M') {
                    iconoSexo.className = 'input-group-text text-white';
                    iconoSexo.style.backgroundColor = '#0d6efd';
                    iconoSexo.innerHTML = '<i class="bi bi-gender-male"></i>';
                    if (sexoInput) sexoInput.value = 'Masculino';
                } else if (sexo === 'Femenino' || sexo === 'F') {
                    iconoSexo.className = 'input-group-text text-white';
                    iconoSexo.style.backgroundColor = '#f06292';
                    iconoSexo.innerHTML = '<i class="bi bi-gender-female"></i>';
                    if (sexoInput) sexoInput.value = 'Femenino';
                } else {
                    iconoSexo.className = 'input-group-text d-none';
                    iconoSexo.innerHTML = '';
                    if (sexoInput) sexoInput.value = '';
                }
            }

            // Al cargar la página
            actualizarIconoSexo(document.getElementById('sexo')?.value);

            // Al cambiar el select
            document.getElementById('sexo')?.addEventListener('change', function() {
                actualizarIconoSexo(this.value);
            });

            // ---------------------------
            // Visor PDF
            // ---------------------------
            const input = document.getElementById('archivo_pdf');
            const visor = document.getElementById('visor-pdf');
            const visorContainer = document.getElementById('visor-container');
            const infoArchivo = document.getElementById('info-archivo');

            input?.addEventListener('change', function() {
                const file = this.files[0];
                if (file && file.type === 'application/pdf') {
                    const fileURL = URL.createObjectURL(file);
                    visor.src = fileURL;
                    visorContainer.style.display = 'block';
                    infoArchivo.innerHTML =
                        `<small><strong>Archivo seleccionado:</strong> ${file.name}<br><strong>Tamaño:</strong> ${(file.size / 1024).toFixed(2)} KB</small>`;
                } else {
                    visorContainer.style.display = 'none';
                    infoArchivo.innerHTML =
                        `<small class="text-danger"><strong>No se ha seleccionado ningún archivo válido.</strong></small>`;
                }
            });

            // ---------------------------
            // Mostrar hora
            // ---------------------------
            document.getElementById('btnHora')?.addEventListener('click', function() {
                const inputHora = document.getElementById('hora_nacimiento');
                inputHora?.focus();
                if (typeof inputHora.showPicker === 'function') {
                    inputHora.showPicker();
                }
            });

            // ---------------------------
            // Crear folio
            // ---------------------------
            const libroSelect = document.getElementById('libro_select');
            const numeroFolioInput = document.getElementById('nuevo_numero_folio');
            const confirmarBtn = document.getElementById('confirmarCrearFolio');
            const loadingFolio = document.getElementById('loading-folio');
            const folioSelect = document.getElementById('id_folio');

            libroSelect?.addEventListener('change', function() {
                const libroId = this.value;
                confirmarBtn.disabled = true;
                numeroFolioInput.value = '';

                if (libroId) {
                    loadingFolio.classList.remove('d-none');
                    fetch(`/nacimiento/folios/siguiente-numero/${libroId}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token
                            }
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                numeroFolioInput.value = data.siguienteNumero;
                                confirmarBtn.disabled = false;
                                numeroFolioInput.classList.add('is-valid');
                                setTimeout(() => numeroFolioInput.classList.remove('is-valid'), 2000);
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(() => alert('Error al obtener número de folio.'))
                        .finally(() => loadingFolio.classList.add('d-none'));
                }
            });

            confirmarBtn?.addEventListener('click', function() {
                const libroId = libroSelect.value;
                if (!libroId) return alert('Seleccione un libro');

                confirmarBtn.disabled = true;
                confirmarBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Creando...';

                fetch('/nacimiento/folios/crear', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            id_libro: parseInt(libroId)
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.folio) {
                            const f = data.folio;
                            const nuevaOpcion = new Option(
                                `Folio ${f.numero_folio} - Libro ${f.libro.numero_libro}`, f
                                .id_folio, true, true);
                            folioSelect.add(nuevaOpcion);
                            folioSelect.value = f.id_folio;
                            bootstrap.Modal.getInstance(document.getElementById('crearFolioModal'))
                                ?.hide();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(() => alert('Error al crear folio.'))
                    .finally(() => {
                        confirmarBtn.disabled = false;
                        confirmarBtn.innerHTML = '<i class="fas fa-save me-1"></i>Crear Folio';
                    });
            });

            // ---------------------------
            // Combos región / provincia / distrito
            // ---------------------------
            const region = document.getElementById('region');
            const provincia = document.getElementById('provincia');
            const distrito = document.getElementById('distrito');

            const selectedRegion = region?.value;
            const selectedProvincia = provincia?.querySelector('option[selected]')?.value || provincia?.value;
            const selectedDistrito = distrito?.querySelector('option[selected]')?.value || distrito?.value;

            if (selectedRegion) {
                fetch(`/provincias/${selectedRegion}`)
                    .then(res => res.json())
                    .then(data => {
                        provincia.disabled = false;
                        provincia.innerHTML = '<option value="">Seleccione provincia</option>' +
                            data.map(p =>
                                `<option value="${p.id_provincia}" ${p.id_provincia == selectedProvincia ? 'selected' : ''}>${p.nombre}</option>`
                            ).join('');

                        // Si ya hay provincia, cargar también distritos
                        if (selectedProvincia) {
                            fetch(`/distritos/${selectedProvincia}`)
                                .then(res => res.json())
                                .then(data => {
                                    distrito.disabled = false;
                                    distrito.innerHTML = '<option value="">Seleccione distrito</option>' +
                                        data.map(d =>
                                            `<option value="${d.id_distrito}" ${d.id_distrito == selectedDistrito ? 'selected' : ''}>${d.nombre}</option>`
                                        ).join('');
                                });
                        }
                    });
            }

            region?.addEventListener('change', function() {
                provincia.innerHTML = '<option value="">Cargando...</option>';
                provincia.disabled = true;
                distrito.innerHTML = '<option value="">Seleccione distrito</option>';
                distrito.disabled = true;

                if (!this.value) return;

                fetch(`/provincias/${this.value}`)
                    .then(res => res.json())
                    .then(data => {
                        provincia.disabled = false;
                        provincia.innerHTML = '<option value="">Seleccione provincia</option>' +
                            data.map(p => `<option value="${p.id_provincia}">${p.nombre}</option>`)
                            .join('');
                    })
                    .catch(() => alert('Error al cargar provincias'));
            });

            provincia?.addEventListener('change', function() {
                distrito.innerHTML = '<option value="">Cargando...</option>';
                distrito.disabled = true;

                if (!this.value) return;

                fetch(`/distritos/${this.value}`)
                    .then(res => res.json())
                    .then(data => {
                        distrito.disabled = false;
                        distrito.innerHTML = '<option value="">Seleccione distrito</option>' +
                            data.map(d => `<option value="${d.id_distrito}">${d.nombre}</option>`).join(
                                '');
                    })
                    .catch(() => alert('Error al cargar distritos'));
            });
        });
    </script>
@endsection
