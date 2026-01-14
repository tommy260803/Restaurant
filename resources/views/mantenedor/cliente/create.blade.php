@extends('layouts.plantilla')

@section('contenido')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <div class="container">
        <h4 class="text-primary mb-3"><i class="fas fa-baby me-2"></i>Registro de Acta de Nacimiento</h4>

        <form action="{{ route('nacimiento.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="card h-100">
                        <h5 class="card-title mb-0 text-center bg-success py-2 text-white">📄 Datos Registrales</h5>

                        <!-- Inicio del formulario con Bootstrap Icons -->
                        <div class="card-body" style="height:70vh;overflow-y:auto">

                            <!-- Información del Acta -->
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-2 fs-5">
                                <i class="bi bi-file-earmark-text me-2"></i>Información del Acta
                            </h6>
                            <div class="row mb-2">
                                <div class="col-6 mb-2">
                                    <label class="mb-2">Número de Acta</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light" value="{{ $numeroFormateado }}"
                                            readonly>
                                        <span class="input-group-text bg-dark text-white" style="pointer-events: none;">
                                            <i class="bi bi-hash"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-6 mb-2">
                                    <label class="mb-2">Fecha de Registro</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light" value="{{ date('d/m/Y') }}"
                                            readonly>
                                        <span class="input-group-text bg-dark text-white" style="pointer-events: none;">
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
                                                    {{ old('id_folio') == $folio->id_folio ? 'selected' : '' }}>
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
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
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
                                    <div class="input-group">
                                        <input type="text" name="nombre_completo" id="nombre_completo"
                                            class="form-control @error('id_recien_nacido') is-invalid @enderror"
                                            value="{{ old('nombre_completo') }}" readonly>
                                        <input type="hidden" name="id_recien_nacido" id="id_recien_nacido"
                                            value="{{ old('id_recien_nacido') }}">
                                        <button type="button" id="btnRecienNacido"
                                            class="btn {{ old('id_recien_nacido') ? 'btn-danger' : 'btn-success' }}"
                                            {!! old('id_recien_nacido')
                                                ? 'onclick="borrarRecienNacido()"'
                                                : 'data-bs-toggle="modal" data-bs-target="#modalRecienNacido"' !!}>
                                            <i class="bi {{ old('id_recien_nacido') ? 'bi-x-circle' : 'bi-search' }}"></i>
                                            {{ old('id_recien_nacido') ? 'Borrar Datos' : 'Buscar Recién Nacido' }}
                                        </button>
                                    </div>
                                    @error('id_recien_nacido')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="sexo_mostrar" class="mb-2">Sexo</label>
                                    <div class="input-group">
                                        <input type="text" id="sexo_mostrar" class="form-control" readonly
                                            placeholder="Masculino">
                                        <span id="iconoSexo" class="input-group-text text-white"
                                            style="pointer-events: none;">
                                        </span>
                                    </div>
                                    <input type="hidden" name="sexo" id="sexo" value="{{ old('sexo') }}">
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="fecha_nacimiento" class="mb-2">Fecha Nacimiento</label>
                                    <div class="input-group">
                                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                                            class="form-control" required value="{{ old('fecha_nacimiento') }}" readonly>
                                        <span class="input-group-text bg-dark text-white" style="pointer-events: none;">
                                            <i class="bi bi-calendar"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="hora_nacimiento" class="mb-2">Hora Nacimiento</label>
                                    <div class="input-group">
                                        <input type="time" name="hora_nacimiento" id="hora_nacimiento"
                                            class="form-control @error('hora_nacimiento') is-invalid @enderror"
                                            value="{{ old('hora_nacimiento') }}" style="position: relative; z-index: 1;">
                                        <button type="button" class="input-group-text bg-dark text-white" id="btnHora"
                                            style="cursor: pointer;">
                                            <i class="bi bi-clock"></i>
                                        </button>
                                    </div>
                                    @error('hora_nacimiento')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <style>
                                    input[type="time"]::-webkit-calendar-picker-indicator {
                                        display: none;
                                        -webkit-appearance: none;
                                    }

                                    input[type="time"]::-webkit-inner-spin-button {
                                        display: none;
                                    }
                                </style>
                            </div>

                            <!-- Lugar de Nacimiento -->
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-3 fs-5">
                                <i class="bi bi-geo-alt-fill me-2"></i>Lugar de Nacimiento
                            </h6>
                            <div class="row mb-2">
                                <div class="mb-2">
                                    <label for="direccion" class="mb-2">Dirección</label>
                                    <input type="text" name="direccion" id="direccion" class="form-control" required
                                        value="{{ old('direccion') }}" readonly placeholder="Ejemplo: Av. Larco 123">
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="region_nombre" class="mb-2">Región</label>
                                    <input type="text" id="region_nombre" class="form-control" readonly
                                        placeholder="La ibertad">
                                    <input type="hidden" name="id_region" id="id_region"
                                        value="{{ old('id_region') }}">
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="provincia_nombre" class="mb-2">Provincia</label>
                                    <input type="text" id="provincia_nombre" class="form-control" readonly
                                        placeholder="Trujillo">
                                    <input type="hidden" name="id_provincia" id="id_provincia"
                                        value="{{ old('id_provincia') }}">
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="distrito_nombre" class="mb-2">Distrito</label>
                                    <input type="text" id="distrito_nombre" class="form-control" readonly
                                        placeholder="Trujillo">
                                    <input type="hidden" name="id_distrito" id="id_distrito"
                                        value="{{ old('id_distrito') }}">
                                </div>
                            </div>

                            <!-- Datos del Padre -->
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-2 fs-5">
                                <i class="bi bi-gender-male me-2"></i>Datos del Padre
                            </h6>
                            <div class="row mb-2">
                                <div class="mb-2">
                                    <div class="mb-2">
                                        <label for="nombre_padre" class="mb-2">Nombres y Apellidos</label>
                                        <div class="input-group">
                                            <input type="text" name="nombre_padre" id="nombre_padre"
                                                class="form-control @error('dni_padre') is-invalid @enderror"
                                                value="{{ old('nombre_padre') }}" readonly>
                                            <input type="hidden" name="dni_padre" id="dni_padre"
                                                value="{{ old('dni_padre') }}">

                                            <button type="button" id="btnPadre"
                                                class="btn {{ old('dni_padre') ? 'btn-danger' : 'btn-success' }}"
                                                data-rol="padre"
                                                @if (old('dni_padre')) onclick="borrarPadre()"
                                                @else
                                                    data-bs-toggle="modal" data-bs-target="#modalBuscarPersona" @endif>
                                                <i class="bi {{ old('dni_padre') ? 'bi-x-circle' : 'bi-search' }}"></i>
                                                {{ old('dni_padre') ? 'Borrar Datos' : 'Buscar Persona' }}
                                            </button>
                                        </div>
                                        @error('dni_padre')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Datos de la Madre -->
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-2 fs-5">
                                <i class="bi bi-gender-female me-2"></i>Datos de la Madre
                            </h6>
                            <div class="row mb-2">
                                <div class="mb-2">
                                    <label for="nombre_madre" class="mb-2">Nombres y Apellidos</label>
                                    <div class="input-group">
                                        <input type="text" name="nombre_madre" id="nombre_madre"
                                            class="form-control @error('dni_madre') is-invalid @enderror"
                                            value="{{ old('nombre_madre') }}" readonly>
                                        <input type="hidden" name="dni_madre" id="dni_madre"
                                            value="{{ old('dni_madre') }}">

                                        <button type="button" id="btnMadre"
                                            class="btn {{ old('dni_madre') ? 'btn-danger' : 'btn-success' }}"
                                            data-rol="madre"
                                            @if (old('dni_madre')) onclick="borrarMadre()"
                                            @else
                                                data-bs-toggle="modal" data-bs-target="#modalBuscarPersona" @endif>
                                            <i class="bi {{ old('dni_madre') ? 'bi-x-circle' : 'bi-search' }}"></i>
                                            {{ old('dni_madre') ? 'Borrar Datos' : 'Buscar Persona' }}
                                        </button>
                                    </div>
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

                <div class="col-md-6">
                    <div class="card h-100">
                        <h5 class="card-title mb-0 text-center bg-dark py-2 text-white">📎 Documentación</h5>
                        <div class="card-body" style="height:70vh;overflow-y:auto">
                            <div class="mb-3">
                                <label>Acta (PDF)</label>
                                <input type="file" name="ruta_archivo_pdf" class="form-control"
                                    accept="application/pdf" id="archivo_pdf">
                                <small class="text-muted">Archivo opcional. Solo PDF.</small>
                            </div>
                            <div id="visor-container" style="display:none">
                                <iframe id="visor-pdf" width="100%" height="400px" class="rounded border"></iframe>
                            </div>
                            <div id="info-archivo" class="alert alert-info d-none">
                                <small><strong>Archivo:</strong> <span
                                        id="nombre-archivo"></span><br><strong>Tamaño:</strong> <span
                                        id="tamano-archivo"></span></small>
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

    <!-- Modal para seleccionar recién nacido -->
    <div class="modal fade" id="modalRecienNacido" tabindex="-1" aria-labelledby="modalRecienNacidoLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-baby me-2"></i>Seleccionar Recién Nacido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row align-items-center">
                        <div class="col-md-9 mb-3">
                            <input type="text" id="buscarRecienNacido" class="form-control"
                                placeholder="Buscar por Apellido">
                        </div>
                        <div class="col-md-3 text-md-end mb-3">
                            <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal"
                                data-bs-target="#modalCrearRecienNacido">
                                <i class="bi bi-plus-circle me-1"></i> Nuevo
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombres</th>
                                    <th>Apellido Paterno</th>
                                    <th>Apellido Materno</th>
                                    <th>Sexo</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tablaRecienNacidos">
                                @foreach ($recienNacidos as $recienNacido)
                                    <tr class="fila-recien-nacido"
                                        data-nombre="{{ strtolower($recienNacido->nombre . ' ' . $recienNacido->apellido_paterno . ' ' . $recienNacido->apellido_materno) }}"
                                        data-dni="{{ $recienNacido->dni }}">
                                        <td>{{ $recienNacido->id_recien_nacido }}</td>
                                        <td>{{ $recienNacido->nombre }}</td>
                                        <td>{{ $recienNacido->apellido_paterno }}</td>
                                        <td>{{ $recienNacido->apellido_materno }}</td>
                                        <td>
                                            @if ($recienNacido->sexo === 'M')
                                                <span class="badge rounded-pill bg-primary px-3 py-2">
                                                    <i class="bi bi-gender-male me-1"></i> M
                                                </span>
                                            @elseif ($recienNacido->sexo === 'F')
                                                <span class="badge rounded-pill bg-pink text-white px-3 py-2"
                                                    style="background-color: #e83e8c;">
                                                    <i class="bi bi-gender-female me-1"></i> F
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                        </td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-sm btn-success seleccionar-recien-nacido"
                                                data-id="{{ $recienNacido->id_recien_nacido }}"
                                                data-nombre="{{ $recienNacido->nombre }} {{ $recienNacido->apellido_paterno }} {{ $recienNacido->apellido_materno }}"
                                                data-sexo="{{ $recienNacido->sexo }}"
                                                data-fecha="{{ $recienNacido->fecha_nacimiento }}"
                                                data-region-nombre="{{ $recienNacido->distrito->provincia->region->nombre ?? '' }}"
                                                data-provincia-nombre="{{ $recienNacido->distrito->provincia->nombre ?? '' }}"
                                                data-distrito-nombre="{{ $recienNacido->distrito->nombre ?? '' }}"
                                                data-direccion="{{ $recienNacido->direccion ?? '' }}">
                                                <i class="fas fa-check me-1"></i>Seleccionar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div id="spinner-recien-nacido" class="text-center my-3 d-none">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Mensaje cuando no hay resultados -->
                    <div id="mensaje-no-resultados" style="display: none;" class="text-center py-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <p class="mb-2">No se encontraron recién nacidos con ese criterio de búsqueda</p>
                        </div>
                    </div>

                    @if ($recienNacidos->isEmpty())
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>No hay recién nacidos disponibles para
                            registrar acta.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear recién nacido -->
    <div class="modal fade" id="modalCrearRecienNacido" tabindex="-1" aria-labelledby="crearRecienNacidoLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-baby me-2"></i> Registro de Recién Nacido
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('recienNacido.store') }}" method="POST">
                        @csrf

                        <!-- Datos Personales -->
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-person-bounding-box me-1 text-success"></i>Datos del Recién Nacido
                        </h6>

                        <!-- Fila 1: Nombres -->
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="bi bi-person"></i></span>
                                <input type="text" id="nombre" name="nombre"
                                    class="form-control @error('nombre', 'recienNacido') is-invalid @enderror"
                                    value="{{ old('nombre') }}" placeholder="Nombres">
                            </div>
                            @error('nombre', 'recienNacido')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Fila 2: Apellidos -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"><i
                                            class="bi bi-person-vcard"></i></span>
                                    <input type="text" id="apellido_paterno" name="apellido_paterno"
                                        class="form-control @error('apellido_paterno', 'recienNacido') is-invalid @enderror"
                                        value="{{ old('apellido_paterno') }}" placeholder="Apellido Paterno">
                                </div>
                                @error('apellido_paterno', 'recienNacido')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"><i
                                            class="bi bi-person-vcard"></i></span>
                                    <input type="text" id="apellido_materno" name="apellido_materno"
                                        class="form-control @error('apellido_materno', 'recienNacido') is-invalid @enderror"
                                        value="{{ old('apellido_materno') }}" placeholder="Apellido Materno">
                                </div>
                                @error('apellido_materno', 'recienNacido')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Fila 3: Fecha y Dirección -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"><i
                                            class="bi bi-calendar-event"></i></span>
                                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                                        class="form-control @error('fecha_nacimiento', 'recienNacido') is-invalid @enderror"
                                        value="{{ old('fecha_nacimiento') }}">
                                </div>
                                @error('fecha_nacimiento', 'recienNacido')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"><i
                                            class="bi bi-geo-alt"></i></span>
                                    <input type="text" id="direccion_recien" name="direccion_recien"
                                        class="form-control @error('direccion_recien', 'recienNacido') is-invalid @enderror"
                                        value="{{ old('direccion_recien') }}" placeholder="Dirección">
                                </div>
                                @error('direccion_recien', 'recienNacido')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Sexo -->
                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i
                                        class="bi bi-gender-ambiguous"></i></span>
                                <select id="sexo" name="sexo"
                                    class="form-select @error('sexo', 'recienNacido') is-invalid @enderror">
                                    <option value="">Seleccione</option>
                                    <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                                </select>
                            </div>
                            @error('sexo', 'recienNacido')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ubicación -->
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-geo me-1 text-success"></i>Ubicación de Nacimiento
                        </h6>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"><i class="bi bi-geo"></i></span>
                                    <select id="region" name="region"
                                        class="form-select @error('region', 'recienNacido') is-invalid @enderror">
                                        <option value="">Seleccione región</option>
                                        @foreach ($regiones as $region)
                                            <option value="{{ $region->id_region }}"
                                                {{ old('region') == $region->id_region ? 'selected' : '' }}>
                                                {{ $region->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('region', 'recienNacido')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"><i
                                            class="bi bi-signpost-2"></i></span>
                                    <select id="provincia" name="provincia"
                                        class="form-select @error('provincia', 'recienNacido') is-invalid @enderror"
                                        {{ old('region') ? '' : 'disabled' }}>
                                        <option value="">Seleccione provincia</option>
                                    </select>
                                </div>
                                @error('provincia', 'recienNacido')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"><i
                                            class="bi bi-geo-fill"></i></span>
                                    <select id="distrito" name="id_distrito_nac"
                                        class="form-select @error('id_distrito_nac', 'recienNacido') is-invalid @enderror"
                                        {{ old('provincia') ? '' : 'disabled' }}>
                                        <option value="">Seleccione distrito</option>
                                    </select>
                                </div>
                                @error('id_distrito_nac', 'recienNacido')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save me-1"></i>Guardar
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (session('mostrar_modal_recien_nacido') || session('abrir_modal_recien_nacido') || $errors->recienNacido->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style = '';

                @if (session('mostrar_modal_recien_nacido') || $errors->recienNacido->any())
                    new bootstrap.Modal(document.getElementById('modalCrearRecienNacido')).show();
                @endif

                @if (session('abrir_modal_recien_nacido'))
                    new bootstrap.Modal(document.getElementById('modalRecienNacido')).show();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Guardado exitosamente!',
                        text: '{{ session('datos') }}',
                        confirmButtonColor: '#198754',
                        confirmButtonText: 'Aceptar'
                    });
                @endif
            });
        </script>
    @endif

    <!-- Modal para seleccionar persona -->
    <div class="modal fade" id="modalBuscarPersona" tabindex="-1" aria-labelledby="modalBuscarPersonaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-lines-fill me-2"></i>Seleccionar Persona</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="row align-items-center">
                        <div class="col-md-12 mb-3">
                            <input type="text" id="buscarPersona" class="form-control"
                                placeholder="Buscar por Apellido o DNI">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>DNI</th>
                                    <th>Nombre Completo</th>
                                    <th>Sexo</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tablaPersonas">
                                @foreach ($personas as $persona)
                                    <tr class="fila-persona" data-sexo="{{ $persona->sexo }}">
                                        <td>{{ $persona->dni }}</td>
                                        <td>{{ $persona->nombres }} {{ $persona->apellido_paterno }}
                                            {{ $persona->apellido_materno }}</td>
                                        <td>
                                            @if ($persona->sexo === 'M')
                                                <span class="badge bg-primary">Masculino</span>
                                            @elseif ($persona->sexo === 'F')
                                                <span class="badge bg-pink text-white"
                                                    style="background-color: #e83e8c;">Femenino</span>
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-success seleccionar-persona"
                                                data-dni="{{ $persona->dni }}"
                                                data-nombre="{{ $persona->nombres }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}">
                                                <i class="fas fa-check me-1"></i>Seleccionar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div id="spinner-persona" class="text-center my-3 d-none">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Mensaje cuando no hay resultados -->
                    <div id="mensaje-no-resultados-persona" style="display: none;" class="text-center py-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <p class="mb-2">No se encontraron personas con ese criterio de búsqueda</p>
                        </div>
                    </div>

                    @if ($personas->isEmpty())
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>No hay personas registradas.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ---------------------------
            // Token CSRF global
            // ---------------------------
            const token = document.querySelector('meta[name="csrf-token"]')?.content ||
                document.querySelector('input[name="_token"]')?.value;

            // ---------------------------
            // Botón Buscar/Borrar Recién Nacido
            // ---------------------------

            const btnBuscarRecienNacido = document.getElementById('btnRecienNacido');
            const btnBuscarPadre = document.getElementById('btnPadre');
            const btnBuscarMadre = document.getElementById('btnMadre');
            const iconoBuscar = '<i class="bi bi-search"></i> Buscar Recién Nacido';
            const iconoBorrar = '<i class="bi bi-x-circle"></i> Borrar Datos';

            function cambiarABotonBorrar() {
                if (btnBuscarRecienNacido) {
                    btnBuscarRecienNacido.classList.remove('btn-dark');
                    btnBuscarRecienNacido.classList.add('btn-danger');
                    btnBuscarRecienNacido.innerHTML = iconoBorrar;
                    btnBuscarRecienNacido.removeAttribute('data-bs-toggle');
                    btnBuscarRecienNacido.removeAttribute('data-bs-target');
                }
            }

            function cambiarABotonBuscar() {
                if (btnBuscarRecienNacido) {
                    btnBuscarRecienNacido.classList.remove('btn-danger');
                    btnBuscarRecienNacido.classList.add('btn-success');
                    btnBuscarRecienNacido.innerHTML = iconoBuscar;
                    btnBuscarRecienNacido.setAttribute('data-bs-toggle', 'modal');
                    btnBuscarRecienNacido.setAttribute('data-bs-target', '#modalRecienNacido');
                }
            }

            // ---------------------------
            // Al seleccionar recién nacido
            // ---------------------------
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.seleccionar-recien-nacido');
                if (btn) {
                    const nombre = btn.dataset.nombre || '';
                    const id = btn.dataset.id || '';
                    const sexo = btn.dataset.sexo || '';
                    const fecha = btn.dataset.fecha || '';
                    const direccion = btn.dataset.direccion || '';
                    const regionNombre = btn.dataset.regionNombre || '';
                    const provinciaNombre = btn.dataset.provinciaNombre || '';
                    const distritoNombre = btn.dataset.distritoNombre || '';

                    // Llenar campos
                    document.getElementById('nombre_completo').value = nombre;
                    document.getElementById('id_recien_nacido').value = id;

                    const sexoTexto = sexo === 'M' ? 'Masculino' : (sexo === 'F' ? 'Femenino' : '');
                    document.getElementById('sexo').value = sexo;
                    document.getElementById('sexo_mostrar').value = sexoTexto;
                    actualizarIconoSexo(sexo);
                    document.getElementById('fecha_nacimiento').value = fecha;

                    document.getElementById('direccion').value = direccion;
                    document.getElementById('region_nombre').value = regionNombre;
                    document.getElementById('provincia_nombre').value = provinciaNombre;
                    document.getElementById('distrito_nombre').value = distritoNombre;

                    // Cerrar modal
                    bootstrap.Modal.getInstance(document.getElementById('modalRecienNacido'))?.hide();

                    // Cambiar botón
                    cambiarABotonBorrar();
                }
            });

            // ---------------------------
            // Borrar datos del recién nacido
            // ---------------------------
            btnBuscarRecienNacido?.addEventListener('click', function() {
                if (this.classList.contains('btn-danger')) {
                    document.getElementById('nombre_completo').value = '';
                    document.getElementById('id_recien_nacido').value = '';
                    document.getElementById('sexo').value = '';
                    document.getElementById('sexo_mostrar').value = '';
                    document.getElementById('fecha_nacimiento').value = '';
                    document.getElementById('direccion').value = '';
                    document.getElementById('region_nombre').value = '';
                    document.getElementById('provincia_nombre').value = '';
                    document.getElementById('distrito_nombre').value = '';
                    cambiarABotonBuscar();
                }
            });

            // ---------------------------
            // Combos región / provincia / distrito
            // ---------------------------
            const region = document.getElementById('region');
            const provincia = document.getElementById('provincia');
            const distrito = document.getElementById('distrito');

            region?.addEventListener('change', function() {
                provincia.innerHTML = '<option value="">Cargando...</option>';
                distrito.innerHTML = '<option value="">Seleccione distrito</option>';
                distrito.disabled = true;

                fetch(`/provincias/${this.value}`)
                    .then(res => res.json())
                    .then(data => {
                        provincia.disabled = false;
                        provincia.innerHTML = '<option value="">Seleccione provincia</option>' +
                            data.map(p => `<option value="${p.id_provincia}">${p.nombre}</option>`)
                            .join('');
                    });
            });

            provincia?.addEventListener('change', function() {
                distrito.innerHTML = '<option value="">Cargando...</option>';
                fetch(`/distritos/${this.value}`)
                    .then(res => res.json())
                    .then(data => {
                        distrito.disabled = false;
                        distrito.innerHTML = '<option value="">Seleccione distrito</option>' +
                            data.map(d => `<option value="${d.id_distrito}">${d.nombre}</option>`).join(
                                '');
                    });
            });

            // ---------------------------
            // Visor PDF del archivo
            // ---------------------------
            const archivoInput = document.getElementById('archivo_pdf');
            const visorContainer = document.getElementById('visor-container');
            const visorPDF = document.getElementById('visor-pdf');
            const infoArchivo = document.getElementById('info-archivo');

            archivoInput?.addEventListener('change', function() {
                const file = this.files[0];
                if (file?.type === 'application/pdf') {
                    document.getElementById('nombre-archivo').textContent = file.name;
                    document.getElementById('tamano-archivo').textContent = (file.size / 1024 / 1024)
                        .toFixed(2) + ' MB';
                    infoArchivo.classList.remove('d-none');
                    visorPDF.src = URL.createObjectURL(file);
                    visorContainer.style.display = 'block';
                } else {
                    visorContainer.style.display = 'none';
                    infoArchivo.classList.add('d-none');
                }
            });

            // ---------------------------
            // Auto-generar número de folio
            // ---------------------------
            const libroSelect = document.getElementById('libro_select');
            const numeroFolioInput = document.getElementById('nuevo_numero_folio');
            const confirmarBtn = document.getElementById('confirmarCrearFolio');
            const loadingFolio = document.getElementById('loading-folio');

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

            // ---------------------------
            // Crear nuevo folio (POST)
            // ---------------------------
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
                            const folioSelect = document.getElementById('id_folio');
                            const f = data.folio;
                            const nuevaOpcion = new Option(
                                `Folio ${f.numero_folio} - Libro ${f.libro.numero_libro}`,
                                f.id_folio,
                                true,
                                true
                            );
                            folioSelect.add(nuevaOpcion);
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
            // Restaurar datos temporales (acta)
            // ---------------------------
            const datosGuardados = sessionStorage.getItem('formulario_acta_temp');
            if (datosGuardados) {
                const datos = JSON.parse(datosGuardados);
                Object.keys(datos).forEach(key => {
                    const el = document.getElementById(key);
                    if (el && datos[key]) el.value = datos[key];
                });
                sessionStorage.removeItem('formulario_acta_temp');
            }

            // ---------------------------
            // Buscar Personas con respecto al género
            // ---------------------------
            let rolSeleccionado = null;

            // Iconos y textos
            const iconoBuscarPersona = '<i class="bi bi-search"></i> Buscar Persona';
            const iconoBorrarDatos = '<i class="bi bi-x-circle"></i> Borrar Datos';

            function cambiarABotonBorrarPersona(btnId) {
                const btn = document.getElementById(btnId);
                if (btn) {
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-danger');
                    btn.innerHTML = iconoBorrarDatos;
                    btn.removeAttribute('data-bs-toggle');
                    btn.removeAttribute('data-bs-target');
                }
            }

            function cambiarABotonBuscarPersona(btnId) {
                const btn = document.getElementById(btnId);
                if (btn) {
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-success');
                    btn.innerHTML = iconoBuscarPersona;
                    btn.setAttribute('data-bs-toggle', 'modal');
                    btn.setAttribute('data-bs-target', '#modalBuscarPersona');
                }
            }

            // Filtrar personas al mostrar el modal
            const modalPersona = document.getElementById('modalBuscarPersona');
            modalPersona?.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                rolSeleccionado = button?.dataset.rol;

                // Validar rol antes de filtrar
                if (!rolSeleccionado) return;

                // Filtrar filas por sexo
                document.querySelectorAll('.fila-persona').forEach(fila => {
                    const sexo = fila.dataset.sexo;
                    if ((rolSeleccionado === 'padre' && sexo === 'M') ||
                        (rolSeleccionado === 'madre' && sexo === 'F')) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                });
            });

            // Selección de persona
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.seleccionar-persona');
                if (btn) {
                    const nombre = btn.dataset.nombre;
                    const dni = btn.dataset.dni;

                    if (rolSeleccionado === 'padre') {
                        document.getElementById('nombre_padre').value = nombre;
                        document.getElementById('dni_padre').value = dni;
                        cambiarABotonBorrarPersona('btnPadre');
                    } else if (rolSeleccionado === 'madre') {
                        document.getElementById('nombre_madre').value = nombre;
                        document.getElementById('dni_madre').value = dni;
                        cambiarABotonBorrarPersona('btnMadre');
                    }

                    bootstrap.Modal.getInstance(document.getElementById('modalBuscarPersona'))?.hide();
                }
            });

            // Borrar datos
            document.getElementById('btnPadre')?.addEventListener('click', function() {
                if (this.classList.contains('btn-danger')) {
                    document.getElementById('nombre_padre').value = '';
                    document.getElementById('dni_padre').value = '';
                    cambiarABotonBuscarPersona('btnPadre');
                }
            });

            document.getElementById('btnMadre')?.addEventListener('click', function() {
                if (this.classList.contains('btn-danger')) {
                    document.getElementById('nombre_madre').value = '';
                    document.getElementById('dni_madre').value = '';
                    cambiarABotonBuscarPersona('btnMadre');
                }
            });

            // Restaurar botones a estado rojo si hay datos cargados por old()
            if (document.getElementById('dni_padre')?.value) {
                cambiarABotonBorrarPersona('btnPadre');
            }
            if (document.getElementById('dni_madre')?.value) {
                cambiarABotonBorrarPersona('btnMadre');
            }
            if (document.getElementById('id_recien_nacido')?.value) {
                cambiarABotonBorrar();
            }

            function borrarRecienNacido() {
                document.getElementById('nombre_completo').value = '';
                document.getElementById('id_recien_nacido').value = '';
                const btn = document.getElementById('btnRecienNacido');
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-success');
                btn.innerHTML = '<i class="bi bi-search"></i> Buscar Recién Nacido';
                btn.setAttribute('data-bs-toggle', 'modal');
                btn.setAttribute('data-bs-target', '#modalRecienNacido');
                btn.removeAttribute('onclick');
            }

            function borrarPadre() {
                document.getElementById('nombre_padre').value = '';
                document.getElementById('dni_padre').value = '';
                const btn = document.getElementById('btnPadre');
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-success');
                btn.innerHTML = '<i class="bi bi-search"></i> Buscar Persona';
                btn.setAttribute('data-bs-toggle', 'modal');
                btn.setAttribute('data-bs-target', '#modalBuscarPersona');
                btn.setAttribute('data-rol', 'padre');
                btn.removeAttribute('onclick');
            }

            function borrarMadre() {
                document.getElementById('nombre_madre').value = '';
                document.getElementById('dni_madre').value = '';
                const btn = document.getElementById('btnMadre');
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-success');
                btn.innerHTML = '<i class="bi bi-search"></i> Buscar Persona';
                btn.setAttribute('data-bs-toggle', 'modal');
                btn.setAttribute('data-bs-target', '#modalBuscarPersona');
                btn.setAttribute('data-rol', 'madre');
                btn.removeAttribute('onclick');
            }
        });

        document.getElementById('btnHora')?.addEventListener('click', () => {
            document.getElementById('hora_nacimiento')._flatpickr.open();
        });

        function actualizarIconoSexo(sexo) {
            const iconoSexo = document.getElementById('iconoSexo');

            if (!iconoSexo) return;

            if (sexo === 'M') {
                iconoSexo.className = 'input-group-text bg-primary text-white';
                iconoSexo.innerHTML = '<i class="bi bi-gender-male"></i>';
            } else if (sexo === 'F') {
                iconoSexo.className = 'input-group-text text-white';
                iconoSexo.style.backgroundColor = '#f06292';
                iconoSexo.innerHTML = '<i class="bi bi-gender-female"></i>';
            } else {
                iconoSexo.className = 'input-group-text d-none';
                iconoSexo.innerHTML = '';
            }
        }

        document.getElementById('btnHora').addEventListener('click', function() {
            const inputHora = document.getElementById('hora_nacimiento');
            inputHora.showPicker?.();
            inputHora.focus();
        });

        const modalCrear = document.getElementById('modalCrearRecienNacido');

        modalCrear.addEventListener('hidden.bs.modal', function() {
            // Redirige a la misma ruta limpia para que Laravel borre los errores
            window.location.href = "{{ route('nacimiento.create') }}";
        });
    </script>
@endsection
