@extends('layouts.plantilla')

@section('contenido')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <div class="container">
        <h4 class="text-warning mb-3"><i class="fas fa-edit me-2"></i>Editar Acta de Defunción</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Corrige los errores:</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('defuncion.update', $acta->id_acta_defuncion) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row">
                <!-- Columna Izquierda: Datos Registrales -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <h5 class="card-title mb-0 text-center bg-warning py-2 text-dark">📝 Editar Datos del Acta</h5>
                        <div class="card-body" style="height:70vh;overflow-y:auto">
                            
                            {{-- Información del Acta --}}
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-2 fs-5">
                                <i class="bi bi-file-earmark-text me-2"></i>Información del Acta
                            </h6>
                            <div class="row mb-2">
                                <div class="col-6 mb-2">
                                    <label class="mb-2">Número de Acta</label>
                                    <input type="text" class="form-control bg-light" value="ACTA-{{ str_pad($acta->id_acta_defuncion, 5, '0', STR_PAD_LEFT) }}" readonly>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="mb-2">Fecha de Registro</label>
                                    <input type="text" class="form-control bg-light" value="{{ $acta->fecha_registro ? \Carbon\Carbon::parse($acta->fecha_registro)->format('d/m/Y') : 'N/D' }}" readonly>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="id_folio" class="mb-2">Folio</label>
                                    <div class="input-group">
                                        <select name="id_folio" id="id_folio" class="form-select @error('id_folio') is-invalid @enderror" required>
                                            <option value="">Seleccione un folio</option>
                                            @foreach($folios as $folio)
                                                <option value="{{ $folio->id_folio }}" {{ old('id_folio', $acta->id_folio) == $folio->id_folio ? 'selected' : '' }}>
                                                    Folio {{ $folio->numero_folio }} - Libro {{ $folio->libro->numero_libro ?? 'N/A' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#crearFolioModal">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    @error('id_folio')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Datos del Fallecido --}}
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-3 fs-5">
                                <i class="bi bi-person-x me-2"></i>Datos del Fallecido
                            </h6>
                            <div class="mb-2">
                                <label class="mb-2">Persona Fallecida</label>
                                <div class="input-group">
                                    <input type="text" id="NombreFallecido" class="form-control @error('dni_fallecido') is-invalid @enderror" placeholder="Seleccione una persona" readonly required
                                           value="{{ $acta->persona_fallecida->nombre_completo ?? 'Persona no encontrada' }}">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#fallecidoModal">
                                        <i class="bi bi-search"></i> Buscar Persona
                                    </button>
                                </div>
                                <input type="hidden" name="dni_fallecido" id="dni_fallecido" value="{{ old('dni_fallecido', $acta->dni_fallecido) }}">
                                @error('dni_fallecido')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row mb-2">
                                <div class="col-6 mb-2">
                                    <label for="fecha_defuncion" class="mb-2">Fecha de Defunción</label>
                                    <div class="input-group">
                                        <input type="date" name="fecha_defuncion" id="fecha_defuncion" 
                                            class="form-control @error('fecha_defuncion') is-invalid @enderror" 
                                            value="{{ old('fecha_defuncion', $acta->fecha_defuncion) }}" required>
                                        <span class="input-group-text bg-dark text-white" style="pointer-events: none;">
                                            <i class="bi bi-calendar-event"></i>
                                        </span>
                                    </div>
                                    @error('fecha_defuncion')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-6 mb-2">
                                    <label for="causa_defuncion" class="mb-2">Causa de Defunción</label>
                                    <textarea name="causa_defuncion" id="causa_defuncion" 
                                        class="form-control @error('causa_defuncion') is-invalid @enderror" 
                                        rows="3" placeholder="Describa la causa...">{{ old('causa_defuncion', $acta->causa_defuncion) }}</textarea>
                                    @error('causa_defuncion')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Lugar de Defunción --}}
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-3 fs-5">
                                <i class="bi bi-geo-alt-fill me-2"></i>Lugar de Defunción
                            </h6>
                            <div class="row mb-2">
                                <div class="col-md-4 mb-2">
                                    <label for="region_defuncion" class="mb-2">Región</label>
                                    <select id="region_defuncion" class="form-select" required>
                                        <option value="">Seleccione región</option>
                                        @foreach($regiones as $region)
                                            <option value="{{ $region->id_region }}" {{ old('region_defuncion', $acta->distrito->provincia->region->id_region ?? '') == $region->id_region ? 'selected' : '' }}>{{ $region->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="provincia_defuncion" class="mb-2">Provincia</label>
                                    <select id="provincia_defuncion" class="form-select" required>
                                        <option value="">Seleccione provincia</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="id_distrito_def" class="mb-2">Distrito</label>
                                    <select name="id_distrito_def" id="id_distrito_def" 
                                        class="form-select @error('id_distrito_def') is-invalid @enderror" required>
                                        <option value="">Seleccione distrito</option>
                                    </select>
                                    @error('id_distrito_def')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Autoridades --}}
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-3 fs-5">
                                <i class="bi bi-person-badge me-2"></i>Autoridades
                            </h6>
                            <div class="mb-2">
                                <label for="id_usuario" class="mb-2">Usuario que Registra</label>
                                <input type="text" id="NombreUsuario" class="form-control bg-light" readonly
                                    value="{{ $acta->usuario->persona->nombre_completo ?? 'Usuario no encontrado' }}">
                                <input type="hidden" name="id_usuario" value="{{ $acta->id_usuario }}">
                                @error('id_usuario')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="id_alcalde" class="mb-2">Alcalde</label>
                                <input type="text" id="NombreAlcalde" class="form-control bg-light" readonly
                                    value="{{ $acta->alcalde->persona->nombre_completo ?? 'Alcalde no encontrado' }}">
                                <input type="hidden" name="id_alcalde" value="{{ $acta->id_alcalde }}">
                                @error('id_alcalde')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-warning flex-fill">
                                    <i class="bi bi-save me-1"></i>Actualizar Acta
                                </button>
                                <a href="{{ route('defuncion.index') }}" class="btn btn-secondary flex-fill">
                                    <i class="bi bi-arrow-left me-1"></i>Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Documentación -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <h5 class="card-title mb-0 text-center bg-dark py-2 text-white">📎 Documentación</h5>
                        <div class="card-body" style="height:70vh;overflow-y:auto">
                            @if($acta->ruta_archivo_pdf)
                                <div class="alert alert-success mb-3">
                                    <h6><i class="fas fa-file-pdf me-1"></i>Archivo Actual</h6>
                                    <p class="mb-2">Ya existe un archivo PDF para esta acta.</p>
                                    <a href="{{ asset('storage/' . $acta->ruta_archivo_pdf) }}" class="btn btn-sm btn-outline-success" target="_blank">
                                        <i class="fas fa-external-link-alt me-1"></i>Ver Archivo
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-warning mb-3">
                                    <h6><i class="fas fa-exclamation-triangle me-1"></i>Sin Archivo</h6>
                                    <p class="mb-0">Esta acta no tiene archivo PDF asociado.</p>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="mb-2">{{ $acta->ruta_archivo_pdf ? 'Reemplazar' : 'Subir' }} Acta (PDF)</label>
                                <input type="file" name="ruta_archivo_pdf" class="form-control" accept="application/pdf" id="archivo_pdf">
                                <small class="text-muted">Archivo opcional. Solo PDF.</small>
                            </div>
                            <div id="visor-container" style="display:none">
                                <iframe id="visor-pdf" width="100%" height="400px" class="rounded border"></iframe>
                            </div>
                            <div id="info-archivo" class="alert alert-info d-none">
                                <small><strong>Archivo:</strong> <span id="nombre-archivo"></span><br><strong>Tamaño:</strong> <span id="tamano-archivo"></span></small>
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
                        <label for="libro_select" class="mb-2">Libro</label>
                        <select id="libro_select" class="form-select" required>
                            <option value="">Seleccione un libro</option>
                            @foreach($libros as $libro)
                                <option value="{{ $libro->id_libro }}">Libro {{ $libro->numero_libro }} - {{ $libro->tipo_libro }} ({{ $libro->anio }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="mb-2">Número de Folio (Automático)</label>
                        <input type="number" id="nuevo_numero_folio" class="form-control" readonly placeholder="Seleccione un libro">
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

    <!-- Modal para persona fallecida -->
    <div class="modal fade" id="fallecidoModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Seleccionar Persona Fallecida</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i><strong>Precaución:</strong> Cambiar la persona fallecida modificará completamente el registro.
                    </div>
                    <div class="mb-3">
                        <input type="text" id="buscadorFallecido" class="form-control" placeholder="Buscar por DNI o nombre...">
                    </div>
                    <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>DNI</th>
                                    <th>Nombre</th>
                                    <th>Distrito</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tablaPersonas">
                                @foreach($personas as $persona)
                                    <tr class="persona-row" data-dni="{{ $persona->dni }}" data-nombre="{{ $persona->nombre_completo }}">
                                        <td>{{ $persona->dni }}</td>
                                        <td>{{ $persona->nombre_completo }}</td>
                                        <td>{{ $persona->distrito->nombre ?? 'N/A' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm seleccionar-fallecido" 
                                                    data-dni="{{ $persona->dni }}" data-nombre="{{ $persona->nombre_completo }}">
                                                <i class="fas fa-check"></i> Seleccionar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const token = document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value;
        window.csrfToken = token;

        const datosActuales = {
            regionId: '{{ $acta->distrito->provincia->region->id_region ?? '' }}',
            provinciaId: '{{ $acta->distrito->provincia->id_provincia ?? '' }}',
            distritoId: '{{ $acta->id_distrito_def ?? '' }}'
        };

        // Función para cargar ubicaciones
        const cargarUbicaciones = (url, selectDestino, valorActual = null) => {
            return fetch(url).then(r => r.json()).then(data => {
                selectDestino.innerHTML = '<option value="">Seleccione</option>';
                data.forEach(item => {
                    const value = url.includes('provincias') ? item.id_provincia : item.id_distrito;
                    selectDestino.innerHTML += `<option value="${value}">${item.nombre}</option>`;
                });
                selectDestino.disabled = false;
                if (valorActual) {
                    selectDestino.value = valorActual;
                }
            });
        };

        // Elementos del DOM
        const elements = {
            region: document.getElementById('region_defuncion'),
            provincia: document.getElementById('provincia_defuncion'),
            distrito: document.getElementById('id_distrito_def'),
            libroSelect: document.getElementById('libro_select'),
            numeroFolio: document.getElementById('nuevo_numero_folio'),
            confirmarBtn: document.getElementById('confirmarCrearFolio'),
            loadingFolio: document.getElementById('loading-folio'),
            buscadorFallecido: document.getElementById('buscadorFallecido'),
            tablaPersonas: document.getElementById('tablaPersonas'),
            archivoPdf: document.getElementById('archivo_pdf'),
            visorContainer: document.getElementById('visor-container'),
            infoArchivo: document.getElementById('info-archivo')
        };

        // Cascadas de ubicación
        elements.region.addEventListener('change', function() {
            elements.provincia.disabled = elements.distrito.disabled = true;
            if (this.value) cargarUbicaciones(`/provincias/${this.value}`, elements.provincia);
        });
        
        elements.provincia.addEventListener('change', function() {
            elements.distrito.disabled = true;
            if (this.value) cargarUbicaciones(`/distritos/${this.value}`, elements.distrito);
        });

        // Inicializar cascadas con datos del acta
        if (datosActuales.regionId) {
            setTimeout(() => {
                elements.region.value = datosActuales.regionId;
                cargarUbicaciones(`/provincias/${datosActuales.regionId}`, elements.provincia, datosActuales.provinciaId).then(() => {
                    if (datosActuales.provinciaId) {
                        cargarUbicaciones(`/distritos/${datosActuales.provinciaId}`, elements.distrito, datosActuales.distritoId);
                    }
                });
            }, 100);
        }

        // Auto-generación de folio
        elements.libroSelect.addEventListener('change', function() {
            const libroId = this.value;
            elements.confirmarBtn.disabled = true;
            elements.numeroFolio.value = '';
            
            if (libroId) {
                elements.loadingFolio.classList.remove('d-none');
                fetch(`/folios/siguiente-numero/${libroId}`, { 
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token } 
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        elements.numeroFolio.value = data.siguienteNumero;
                        elements.confirmarBtn.disabled = false;
                    } else alert('Error: ' + data.message);
                })
                .catch(() => alert('Error de conexión'))
                .finally(() => elements.loadingFolio.classList.add('d-none'));
            }
        });

        // Crear folio
        elements.confirmarBtn.addEventListener('click', function() {
            const libroId = elements.libroSelect.value;
            if (!libroId) return alert('Seleccione un libro');

            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Creando...';

            fetch('/folios/crear', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                body: JSON.stringify({ id_libro: parseInt(libroId) })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const folioSelect = document.getElementById('id_folio');
                    folioSelect.add(new Option(`Folio ${data.folio.numero_folio} - Libro ${data.folio.libro.numero_libro}`, data.folio.id_folio, true, true));
                    bootstrap.Modal.getInstance(document.getElementById('crearFolioModal')).hide();
                    
                    // Mostrar mensaje de éxito
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success alert-dismissible fade show';
                    alertDiv.innerHTML = `<i class="fas fa-check-circle me-2"></i>${data.message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
                    document.querySelector('form').insertBefore(alertDiv, document.querySelector('form').firstChild);
                    setTimeout(() => alertDiv.remove(), 5000);
                } else alert('Error: ' + data.message);
            })
            .catch(() => alert('Error de conexión'))
            .finally(() => {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-save me-1"></i>Crear Folio';
            });
        });

        // Buscador de personas fallecidas
        elements.buscadorFallecido.addEventListener('input', function() {
            const termino = this.value.toLowerCase();
            elements.tablaPersonas.querySelectorAll('.persona-row').forEach(fila => {
                const coincide = fila.dataset.dni.toLowerCase().includes(termino) || 
                               fila.dataset.nombre.toLowerCase().includes(termino);
                fila.style.display = coincide ? 'table-row' : 'none';
            });
        });

        // Selección de persona fallecida
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('seleccionar-fallecido')) {
                if (!confirm('¿Cambiar la persona fallecida? Esto modificará el registro.')) return;
                
                document.getElementById('dni_fallecido').value = e.target.dataset.dni;
                document.getElementById('NombreFallecido').value = e.target.dataset.nombre;
                bootstrap.Modal.getInstance(document.getElementById('fallecidoModal')).hide();
            }
        });
        
        // Visor PDF
        elements.archivoPdf.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file?.type === 'application/pdf') {
                document.getElementById('nombre-archivo').textContent = file.name;
                document.getElementById('tamano-archivo').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                elements.infoArchivo.classList.remove('d-none');
                document.getElementById('visor-pdf').src = URL.createObjectURL(file);
                elements.visorContainer.style.display = 'block';
            } else {
                elements.visorContainer.style.display = 'none';
                elements.infoArchivo.classList.add('d-none');
            }
        });
    });
    </script>
@endsection