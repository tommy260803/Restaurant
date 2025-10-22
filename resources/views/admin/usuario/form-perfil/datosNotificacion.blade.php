{{-- Vista principal de notificaciones --}}
@extends('admin.usuario.perfil')


@section('datosUsuario')
    <div class="container-fluid py-4">
        {{-- Encabezado --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold text-primary mb-1">
                            <i class="ri-notification-3-line me-2"></i>Centro de Notificaciones
                        </h2>
                        <p class="text-muted mb-0">Gestión integral de notificaciones del sistema</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary" onclick="marcarTodasLeidas()">
                            <i class="ri-check-double-line me-1"></i>Marcar todas como leídas
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#configuracionModal">
                            <i class="ri-settings-3-line me-1"></i>Configurar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Estadísticas rápidas --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-gradient-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ri-notification-badge-line fs-2"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">No Leídas</h6>
                                <h4 class="mb-0 fw-bold">{{ $notificaciones_no_leidas }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-gradient-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ri-shield-check-line fs-2"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Validaciones</h6>
                                <h4 class="mb-0 fw-bold">{{ $validaciones_pendientes }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-gradient-warning text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ri-money-dollar-circle-line fs-2"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Pagos Pendientes</h6>
                                <h4 class="mb-0 fw-bold">{{ $pagos_pendientes }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-gradient-info text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ri-file-text-line fs-2"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Trámites Hoy</h6>
                                <h4 class="mb-0 fw-bold">{{ $tramites_hoy }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtros y búsqueda --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="{{ route('usuarios.notificaciones', $usuario->id_usuario) }}">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Tipo de Notificación</label>
                                    <select name="tipo" class="form-select">
                                        <option value="">Todos los tipos</option>
                                        <option value="sistema" {{ request('tipo') == 'sistema' ? 'selected' : '' }}>Sistema
                                        </option>
                                        <option value="pago" {{ request('tipo') == 'pago' ? 'selected' : '' }}>Pagos
                                        </option>
                                        <option value="validacion" {{ request('tipo') == 'validacion' ? 'selected' : '' }}>
                                            Validaciones</option>
                                        <option value="tramite" {{ request('tipo') == 'tramite' ? 'selected' : '' }}>
                                            Trámites</option>
                                        <option value="vencimiento"
                                            {{ request('tipo') == 'vencimiento' ? 'selected' : '' }}>Vencimientos</option>
                                        <option value="seguridad" {{ request('tipo') == 'seguridad' ? 'selected' : '' }}>
                                            Seguridad</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Estado</label>
                                    <select name="estado" class="form-select">
                                        <option value="">Todos</option>
                                        <option value="no_leida" {{ request('estado') == 'no_leida' ? 'selected' : '' }}>No
                                            leídas</option>
                                        <option value="leida" {{ request('estado') == 'leida' ? 'selected' : '' }}>Leídas
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Prioridad</label>
                                    <select name="prioridad" class="form-select">
                                        <option value="">Todas</option>
                                        <option value="alta" {{ request('prioridad') == 'alta' ? 'selected' : '' }}>Alta
                                        </option>
                                        <option value="media" {{ request('prioridad') == 'media' ? 'selected' : '' }}>
                                            Media</option>
                                        <option value="baja" {{ request('prioridad') == 'baja' ? 'selected' : '' }}>Baja
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Buscar</label>
                                    <input type="text" name="buscar" class="form-control"
                                        placeholder="Buscar en notificaciones..." value="{{ request('buscar') }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ri-search-line me-1"></i>Filtrar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lista de notificaciones --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="ri-list-check-2 me-2"></i>Notificaciones Recientes
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if ($notificaciones->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach ($notificaciones as $notificacion)
                                    <div class="list-group-item border-0 {{ !$notificacion->leida ? 'bg-light' : '' }}"
                                        data-notificacion-id="{{ $notificacion->id }}">
                                        <div class="d-flex w-100 align-items-start">
                                            {{-- Icono según tipo --}}
                                            <div class="flex-shrink-0 me-3">
                                                <div
                                                    class="avatar-sm rounded-circle d-flex align-items-center justify-content-center
                                                {{ $notificacion->tipo == 'pago'
                                                    ? 'bg-warning'
                                                    : ($notificacion->tipo == 'validacion'
                                                        ? 'bg-info'
                                                        : ($notificacion->tipo == 'sistema'
                                                            ? 'bg-primary'
                                                            : ($notificacion->tipo == 'seguridad'
                                                                ? 'bg-danger'
                                                                : 'bg-success'))) }}">
                                                    <i
                                                        class="
                                                    {{ $notificacion->tipo == 'pago'
                                                        ? 'ri-money-dollar-circle-line'
                                                        : ($notificacion->tipo == 'validacion'
                                                            ? 'ri-shield-check-line'
                                                            : ($notificacion->tipo == 'sistema'
                                                                ? 'ri-settings-3-line'
                                                                : ($notificacion->tipo == 'seguridad'
                                                                    ? 'ri-shield-keyhole-line'
                                                                    : 'ri-file-text-line'))) }}
                                                    text-white"></i>
                                                </div>
                                            </div>

                                            {{-- Contenido --}}
                                            <div class="flex-grow-1">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1 fw-semibold">{{ $notificacion->titulo }}</h6>
                                                    <small
                                                        class="text-muted">{{ $notificacion->created_at->diffForHumans() }}</small>
                                                </div>
                                                <p class="mb-1 text-muted">{{ $notificacion->mensaje }}</p>

                                                {{-- Metadatos --}}
                                                <div class="d-flex align-items-center gap-3 mt-2">
                                                    <span
                                                        class="badge bg-secondary text-uppercase">{{ $notificacion->tipo }}</span>
                                                    <span
                                                        class="badge 
                                                    {{ $notificacion->prioridad == 'alta'
                                                        ? 'bg-danger'
                                                        : ($notificacion->prioridad == 'media'
                                                            ? 'bg-warning'
                                                            : 'bg-success') }}">
                                                        {{ ucfirst($notificacion->prioridad) }}
                                                    </span>
                                                    @if ($notificacion->referencia_id)
                                                        <small class="text-muted">
                                                            <i class="ri-link me-1"></i>Ref:
                                                            {{ $notificacion->referencia_id }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Acciones --}}
                                            <div class="flex-shrink-0 ms-3">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        @if (!$notificacion->leida)
                                                            <li>
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="marcarComoLeida({{ $notificacion->id }})">
                                                                    <i class="ri-check-line me-2"></i>Marcar como leída
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if ($notificacion->url_accion)
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ $notificacion->url_accion }}">
                                                                    <i class="ri-external-link-line me-2"></i>Ver detalles
                                                                </a>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#"
                                                                onclick="eliminarNotificacion({{ $notificacion->id }})">
                                                                <i class="ri-delete-bin-line me-2"></i>Eliminar
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ri-notification-off-line text-muted" style="font-size: 4rem;"></i>
                                <h5 class="mt-3 text-muted">No hay notificaciones</h5>
                                <p class="text-muted mb-0">No se encontraron notificaciones con los filtros aplicados.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Paginación --}}
                    @if ($notificaciones->hasPages())
                        <div class="card-footer bg-white border-top">
                            {{ $notificaciones->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de configuración --}}
    <div class="modal fade" id="configuracionModal" tabindex="-1" aria-labelledby="configuracionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="configuracionModalLabel">
                        <i class="ri-settings-3-line me-2"></i>Configuración de Notificaciones
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="configuracionForm">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <h6 class="fw-semibold mb-3">Tipos de Notificación</h6>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="notif_pagos" checked>
                                    <label class="form-check-label" for="notif_pagos">
                                        <i class="ri-money-dollar-circle-line me-2"></i>Notificaciones de Pagos
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="notif_validaciones" checked>
                                    <label class="form-check-label" for="notif_validaciones">
                                        <i class="ri-shield-check-line me-2"></i>Validaciones de Documentos
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="notif_tramites" checked>
                                    <label class="form-check-label" for="notif_tramites">
                                        <i class="ri-file-text-line me-2"></i>Estado de Trámites
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="notif_vencimientos" checked>
                                    <label class="form-check-label" for="notif_vencimientos">
                                        <i class="ri-calendar-check-line me-2"></i>Vencimientos
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="notif_seguridad" checked>
                                    <label class="form-check-label" for="notif_seguridad">
                                        <i class="ri-shield-keyhole-line me-2"></i>Alertas de Seguridad
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="notif_sistema" checked>
                                    <label class="form-check-label" for="notif_sistema">
                                        <i class="ri-settings-3-line me-2"></i>Notificaciones del Sistema
                                    </label>
                                </div>
                            </div>

                            <div class="col-12">
                                <hr class="my-4">
                                <h6 class="fw-semibold mb-3">Preferencias de Entrega</h6>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="email_notif" checked>
                                    <label class="form-check-label" for="email_notif">
                                        <i class="ri-mail-line me-2"></i>Notificaciones por Email
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="web_notif" checked>
                                    <label class="form-check-label" for="web_notif">
                                        <i class="ri-notification-3-line me-2"></i>Notificaciones Web
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Frecuencia de Resumen</label>
                                <select class="form-select" name="frecuencia_resumen">
                                    <option value="diario">Diario</option>
                                    <option value="semanal" selected>Semanal</option>
                                    <option value="mensual">Mensual</option>
                                    <option value="nunca">Nunca</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Horario de Notificaciones</label>
                                <select class="form-select" name="horario_notif">
                                    <option value="08:00">08:00 AM</option>
                                    <option value="12:00">12:00 PM</option>
                                    <option value="18:00" selected>06:00 PM</option>
                                    <option value="20:00">08:00 PM</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarConfiguracion()">
                        <i class="ri-save-line me-1"></i>Guardar Configuración
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        function marcarComoLeida(id) {
            fetch(`/notificaciones/${id}/marcar-leida`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }

        function marcarTodasLeidas() {
            if (confirm('¿Está seguro de marcar todas las notificaciones como leídas?')) {
                fetch('/notificaciones/marcar-todas-leidas', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
            }
        }

        function eliminarNotificacion(id) {
            if (confirm('¿Está seguro de eliminar esta notificación?')) {
                fetch(`/notificaciones/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
            }
        }

        function guardarConfiguracion() {
            const formData = new FormData(document.getElementById('configuracionForm'));

            fetch('/notificaciones/configuracion', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('configuracionModal')).hide();
                        location.reload();
                    }
                });
        }

        // Actualizar notificaciones en tiempo real cada 30 segundos
        setInterval(function() {
            fetch('/notificaciones/verificar-nuevas', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.nuevas > 0) {
                        location.reload();
                    }
                });
        }, 30000);
    </script>
@endsection
