@extends('layouts.plantilla')

@section('title', 'Cocina - Dashboard')

@section('contenido')
<div class="container-fluid mt-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3"><i class="bi bi-egg-fried me-2"></i> Panel de Cocina</h1>
    <div class="d-flex gap-2">
      <a href="{{ route('cocinero.pedidos') }}" class="btn btn-primary">
        <i class="bi bi-list-task me-1"></i> Ver Pedidos
      </a>
      <a href="{{ route('cocinero.historial') }}" class="btn btn-outline-secondary">
        <i class="bi bi-clock-history me-1"></i> Historial
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
      <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- Estadísticas -->
  <div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
      <div class="card shadow-sm border-start border-warning border-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted mb-1 small">Pendientes</p>
              <h2 class="mb-0 fw-bold text-warning"><span id="stat-pendientes">{{ $pendientes }}</span></h2>
            </div>
            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
              <i class="bi bi-hourglass-split text-warning" style="font-size: 2rem;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card shadow-sm border-start border-info border-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted mb-1 small">En Preparación</p>
              <h2 class="mb-0 fw-bold text-info"><span id="stat-enprep">{{ $enPrep }}</span></h2>
            </div>
            <div class="bg-info bg-opacity-10 p-3 rounded-circle">
              <i class="bi bi-fire text-info" style="font-size: 2rem;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card shadow-sm border-start border-success border-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="text-muted mb-1 small">Preparados Hoy</p>
              <h2 class="mb-0 fw-bold text-success"><span id="stat-preparados">{{ $preparados }}</span></h2>
            </div>
            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
              <i class="bi bi-check2-circle text-success" style="font-size: 2rem;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabla de pedidos pendientes -->
  <div class="card shadow-sm">
    <div class="card-header bg-primary bg-gradient text-white">
      <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i> Pedidos Pendientes Recientes</h5>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="py-3">Hora</th>
              <th>Mesa</th>
              <th>Cliente</th>
              <th>Plato</th>
              <th class="text-center">Cant.</th>
              <th>Observaciones</th>
              <th class="text-center" style="width: 200px;">Acciones</th>
            </tr>
          </thead>
          <tbody id="tabla-pendientes">
            @forelse($pedidos as $p)
              <tr data-id="{{ $p->id }}">
                <td class="fw-bold">
                  <i class="bi bi-clock text-muted me-1"></i>
                  {{ $p->created_at->format('H:i') }}
                </td>
                <td>
                  @if($p->reserva?->mesa?->numero)
                    <span class="badge bg-dark">
                      <i class="bi bi-table me-1"></i>Mesa {{ $p->reserva->mesa->numero }}
                    </span>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td class="fw-semibold">{{ $p->reserva?->nombre_cliente ?? '—' }}</td>
                <td>
                  <i class="bi bi-dish text-primary me-1"></i>
                  {{ $p->plato?->nombre ?? '—' }}
                </td>
                <td class="text-center">
                  <span class="badge bg-info rounded-pill">{{ $p->cantidad }}</span>
                </td>
                <td>
                  <small class="text-muted">{{ Str::limit($p->notas ?? 'Sin observaciones', 30) }}</small>
                </td>
                <td class="text-center">
                  <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-info text-white btn-detalle" data-id="{{ $p->id }}" title="Ver detalle">
                      <i class="bi bi-eye-fill me-1"></i>Ver
                    </button>
                    @if($p->estado === 'Enviado a cocina')
                      <button class="btn btn-sm btn-primary btn-preparar" data-id="{{ $p->id }}" title="Marcar en preparación">
                        <i class="bi bi-fire me-1"></i>Preparar
                      </button>
                    @elseif($p->estado === 'En preparación')
                      <button class="btn btn-sm btn-success btn-preparado" data-id="{{ $p->id }}" title="Marcar como preparado">
                        <i class="bi bi-check2 me-1"></i>Listo
                      </button>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-5">
                  <div class="text-muted">
                    <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                    <p class="mt-3 mb-0">No hay pedidos pendientes</p>
                    <small>Los nuevos pedidos aparecerán aquí automáticamente</small>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer bg-light text-muted small">
      <i class="bi bi-arrow-clockwise me-1"></i>
      Los datos se actualizan automáticamente cada 15 segundos
    </div>
  </div>
</div>

<!-- Modal Detalle -->
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary bg-gradient text-white">
        <h5 class="modal-title">
          <i class="bi bi-info-circle me-2"></i>Detalle del Pedido
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div id="modalDetalleBody">
          <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-3 text-muted">Cargando información...</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-1"></i>Cerrar
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  'use strict';
  
  const CSRF = '{{ csrf_token() }}';
  let modal = null;

  // Esperar a que el DOM esté listo
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  function init() {
    console.log('Inicializando dashboard de cocina...');
    
    // Inicializar modal de Bootstrap
    const modalEl = document.getElementById('modalDetalle');
    if (modalEl) {
      if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        modal = new bootstrap.Modal(modalEl);
        console.log('Modal inicializado correctamente');
      } else {
        console.error('Bootstrap no está disponible');
      }
    }
    
    // Iniciar actualizaciones
    refreshStats();
    refreshPendientes();
    
    setInterval(refreshStats, 10000);
    setInterval(refreshPendientes, 15000);
  }

  // Función para refrescar estadísticas
  async function refreshStats(){
    try {
      const res = await fetch("{{ route('cocinero.api.stats') }}", {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        credentials: 'same-origin'
      });
      
      if (!res.ok) {
        console.error('Error al cargar stats:', res.status, res.statusText);
        return;
      }
      
      const data = await res.json();
      console.log('Stats recibidas:', data);
      
      // Actualizar contadores con animación
      actualizarContador('stat-pendientes', data.pendientes ?? 0);
      actualizarContador('stat-enprep', data.en_preparacion ?? 0);
      actualizarContador('stat-preparados', data.preparados ?? 0);
      
    } catch(e) {
      console.error('Error en refreshStats:', e);
    }
  }

  function actualizarContador(id, valor) {
    const el = document.getElementById(id);
    if (!el) return;
    
    const valorActual = parseInt(el.textContent) || 0;
    if (valorActual !== valor) {
      el.classList.add('actualizado');
      el.textContent = valor;
      setTimeout(() => el.classList.remove('actualizado'), 600);
    }
  }

  // Función para cargar detalle
  async function cargarDetalle(id){
    const bodyEl = document.getElementById('modalDetalleBody');
    if (!bodyEl) return;
    
    bodyEl.innerHTML = `
      <div class="text-center py-4">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
        <p class="mt-3 text-muted">Cargando información...</p>
      </div>`;
    
    try {
      const res = await fetch(`{{ route('cocinero.api.pedido', ':id') }}`.replace(':id', id), {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        credentials: 'same-origin'
      });
      
      if (!res.ok) {
        throw new Error('Error ' + res.status);
      }
      
      const data = await res.json();
      console.log('Detalle recibido:', data);
      
      const html = `
        <div class="row g-3">
          <div class="col-12">
            <div class="alert alert-info mb-0">
              <i class="bi bi-info-circle me-2"></i>
              <strong>Pedido #${data.id}</strong> - Estado: <span class="badge bg-warning">${data.estado || '—'}</span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-body">
                <h6 class="card-title text-primary">
                  <i class="bi bi-person-circle me-2"></i>Información del Cliente
                </h6>
                <hr>
                <p class="mb-2"><strong>Nombre:</strong> ${data.reserva?.cliente || '—'}</p>
                <p class="mb-2"><strong>Mesa:</strong> ${data.reserva?.mesa ? 'Mesa '+data.reserva.mesa : '—'}</p>
                <p class="mb-0"><strong>Personas:</strong> ${data.reserva?.personas || '—'}</p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-body">
                <h6 class="card-title text-primary">
                  <i class="bi bi-clock-history me-2"></i>Detalles de Tiempo
                </h6>
                <hr>
                <p class="mb-2"><strong>Hora de reserva:</strong> ${data.reserva?.hora || '—'}</p>
                <p class="mb-2"><strong>Creado:</strong> ${data.created_at ? new Date(data.created_at).toLocaleString('es-PE') : '—'}</p>
                <p class="mb-0"><strong>Estado:</strong> <span class="badge bg-warning">${data.estado || '—'}</span></p>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="card border-primary">
              <div class="card-body">
                <h6 class="card-title text-primary">
                  <i class="bi bi-dish me-2"></i>Detalle del Plato
                </h6>
                <hr>
                <div class="row">
                  <div class="col-md-8">
                    <p class="mb-2"><strong>Plato:</strong> ${data.plato?.nombre || '—'}</p>
                    ${data.plato?.descripcion ? `<p class="text-muted small mb-0"><em>${data.plato.descripcion}</em></p>` : ''}
                  </div>
                  <div class="col-md-4 text-end">
                    <p class="mb-2"><strong>Cantidad:</strong> <span class="badge bg-info fs-6">${data.cantidad || 0}</span></p>
                    <p class="mb-0"><strong>Precio:</strong> <span class="text-success fw-bold fs-5">S/ ${Number(data.precio || 0).toFixed(2)}</span></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="card bg-light">
              <div class="card-body">
                <h6 class="card-title">
                  <i class="bi bi-chat-left-text me-2"></i>Observaciones
                </h6>
                <hr>
                <div class="p-3 bg-white border rounded">
                  ${data.notas || '<em class="text-muted">Sin observaciones especiales</em>'}
                </div>
              </div>
            </div>
          </div>
        </div>`;
      
      bodyEl.innerHTML = html;
      
    } catch(e) {
      console.error('Error al cargar detalle:', e);
      bodyEl.innerHTML = `
        <div class="alert alert-danger">
          <i class="bi bi-exclamation-triangle me-2"></i>
          <strong>Error:</strong> No se pudo cargar el detalle del pedido
        </div>`;
    }
  }

  // Función para ejecutar acción
  async function postAccion(url, boton){
    try {
      console.log('POST a:', url);
      
      // Guardar contenido original del botón
      const contenidoOriginal = boton.innerHTML;
      boton.disabled = true;
      boton.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Procesando...';
      
      const res = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF,
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        credentials: 'same-origin',
        body: JSON.stringify({})
      });
      
      console.log('Respuesta status:', res.status);
      
      // Restaurar botón
      boton.innerHTML = contenidoOriginal;
      boton.disabled = false;
      
      if (!res.ok) {
        const errorText = await res.text();
        console.error('Error en acción:', res.status, errorText);
        return false;
      }
      
      const data = await res.json();
      console.log('Respuesta exitosa:', data);
      return true;
      
    } catch(e) {
      console.error('Error en postAccion:', e);
      boton.disabled = false;
      return false;
    }
  }

  // Función para refrescar tabla
  async function refreshPendientes(){
    try {
      const res = await fetch("{{ route('cocinero.api.pendientes') }}", {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        credentials: 'same-origin'
      });
      
      if (!res.ok) {
        console.error('Error al cargar pendientes:', res.status, res.statusText);
        return;
      }
      
      const payload = await res.json();
      console.log('Pendientes recibidos:', payload);
      
      const tbody = document.getElementById('tabla-pendientes');
      if (!tbody) {
        console.error('No se encontró el elemento tabla-pendientes');
        return;
      }
      
      if (!payload || !Array.isArray(payload.data)) {
        console.warn('Respuesta inesperada:', payload);
        return;
      }

      if (payload.data.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="7" class="text-center py-5">
              <div class="text-muted">
                <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                <p class="mt-3 mb-0">No hay pedidos pendientes</p>
                <small>Los nuevos pedidos aparecerán aquí automáticamente</small>
              </div>
            </td>
          </tr>`;
        return;
      }

      tbody.innerHTML = payload.data.map(p => {
        // Determinar qué botones mostrar según el estado
        const botonesPendiente = `
          <button class="btn btn-sm btn-info text-white btn-detalle" data-id="${p.id}" title="Ver detalle">
            <i class="bi bi-eye-fill me-1"></i>Ver
          </button>
          <button class="btn btn-sm btn-primary btn-preparar" data-id="${p.id}" title="Marcar en preparación">
            <i class="bi bi-fire me-1"></i>Preparar
          </button>`;
        
        const botonesPreparacion = `
          <button class="btn btn-sm btn-info text-white btn-detalle" data-id="${p.id}" title="Ver detalle">
            <i class="bi bi-eye-fill me-1"></i>Ver
          </button>
          <button class="btn btn-sm btn-success btn-preparado" data-id="${p.id}" title="Marcar como preparado">
            <i class="bi bi-check2 me-1"></i>Listo
          </button>`;
        
        const botones = p.estado === 'En preparación' ? botonesPreparacion : botonesPendiente;
        const badge = p.estado === 'en_preparacion' ? '<span class="badge bg-info ms-2">En Preparación</span>' : '';
        
        return `
          <tr data-id="${p.id}" data-estado="${p.estado}">
            <td class="fw-bold">
              <i class="bi bi-clock text-muted me-1"></i>
              ${p.hora || '—'}
            </td>
            <td>
              ${p.mesa ? `<span class="badge bg-dark"><i class="bi bi-table me-1"></i>Mesa ${p.mesa}</span>` : '<span class="text-muted">—</span>'}
            </td>
            <td class="fw-semibold">${p.cliente || '—'}</td>
            <td>
              <i class="bi bi-dish text-primary me-1"></i>
              ${p.plato || '—'}
            </td>
            <td class="text-center">
              <span class="badge bg-info rounded-pill">${p.cantidad || '—'}</span>
            </td>
            <td>
              <small class="text-muted">${(p.notas || 'Sin observaciones').substring(0, 30)}${(p.notas && p.notas.length > 30) ? '...' : ''}</small>
              ${badge}
            </td>
            <td class="text-center">
              <div class="btn-group" role="group">
                ${botones}
              </div>
            </td>
          </tr>`;
      }).join('');
        
    } catch(e) {
      console.error('Error en refreshPendientes:', e);
    }
  }

  // Delegación de eventos
  document.addEventListener('click', async (ev) => {
    const detalleBtn = ev.target.closest('.btn-detalle');
    const prepBtn = ev.target.closest('.btn-preparar');
    const listoBtn = ev.target.closest('.btn-preparado');
    
    if (detalleBtn) {
      ev.preventDefault();
      const id = detalleBtn.getAttribute('data-id');
      if (!id) {
        console.error('No se encontró ID del pedido');
        return;
      }
      
      console.log('Cargando detalle del pedido:', id);
      await cargarDetalle(id);
      
      if (modal) {
        modal.show();
      } else {
        console.error('Modal no disponible');
        alert('Error: No se puede abrir el modal. Recarga la página.');
      }
      return;
    }
    
    if (prepBtn) {
      ev.preventDefault();
      const id = prepBtn.getAttribute('data-id');
      if (!id) return;
      
      console.log('Marcando pedido en preparación:', id);
      
      const url = `{{ route('cocinero.pedidos.preparar', ':id') }}`.replace(':id', id);
      const ok = await postAccion(url, prepBtn);
      
      if (ok) {
        refreshStats();
        refreshPendientes();
      } else {
        alert('Error al cambiar estado del pedido');
      }
      return;
    }
    
    if (listoBtn) {
      ev.preventDefault();
      const id = listoBtn.getAttribute('data-id');
      if (!id) return;
      
      console.log('Marcando pedido como preparado:', id);
      
      const url = `{{ route('cocinero.pedidos.finalizar', ':id') }}`.replace(':id', id);
      const ok = await postAccion(url, listoBtn);
      
      if (ok) {
        refreshStats();
        refreshPendientes();
      } else {
        alert('Error al cambiar estado del pedido');
      }
      return;
    }
  });
  
})();
</script>
@endpush

@push('styles')
<style>
  .table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
  }
  
  .btn-group .btn {
    font-weight: 500;
  }
  
  .btn-group .btn-detalle:hover {
    background-color: #0bb2d4;
    border-color: #0bb2d4;
  }
  
  .btn-group .btn-preparar:hover {
    background-color: #0b5ed7;
    border-color: #0a58ca;
  }
  
  .btn-group .btn-preparado:hover {
    background-color: #157347;
    border-color: #146c43;
  }
  
  #stat-pendientes, #stat-enprep, #stat-preparados {
    transition: all 0.3s ease;
  }
  
  .actualizado {
    transform: scale(1.2);
    color: #198754 !important;
  }
  
  .card {
    transition: transform 0.2s ease;
  }
  
  .card:hover {
    transform: translateY(-2px);
  }
</style>
@endpush