{{-- filepath: resources/views/proveedores/index.blade.php --}}
@extends('layouts.plantilla')

@section('titulo', 'Gestión de Proveedores')

@section('contenido')
<div class="container-fluid mt-4 px-4">

    {{-- Header --}}
    <div class="mb-4">
        <div class="page-header p-4 rounded-3 mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title mb-2">
                        <i class="fas fa-truck me-2"></i>
                        Gestión de Proveedores
                    </h1>
                    <p class="page-subtitle mb-0">Administra y controla todos los proveedores del sistema</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="stats-badge">
                        <span class="stat-number">{{ $proveedores->total() }}</span>
                        <span class="stat-label">Total</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Botones de acción --}}
        <div class="d-flex flex-wrap gap-2 justify-content-end mb-4">
            <a href="{{ route('proveedor.exportarPDF') }}" class="btn btn-outline-danger" target="_blank">
                <i class="fas fa-file-pdf me-1"></i> Exportar PDF
            </a>
            <a href="{{ route('proveedor.exportarExcel') }}" class="btn btn-outline-success" target="_blank">
                <i class="fas fa-file-excel me-1"></i> Exportar Excel
            </a>
            <a href="{{ route('proveedor.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Nuevo Proveedor
            </a>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small text-muted">Buscar</label>
                    <input name="buscar" type="search" class="form-control" 
                           placeholder="Nombre, email o RUC" value="{{ $buscar ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="activo" {{ ($estado ?? '') == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ ($estado ?? '') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        <option value="bloqueado" {{ ($estado ?? '') == 'bloqueado' ? 'selected' : '' }}>Bloqueado</option>
                    </select>
                </div>
                <div class="col-md-4 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> Buscar
                    </button>
                    <a href="{{ route('proveedor.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo me-1"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Mensajes --}}
    @if (session('success'))
        <div id="mensaje" class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Cards de proveedores --}}
    <div class="row g-4">
        @forelse($proveedores as $item)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card provider-card h-100">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="provider-avatar me-3">
                                <span>{{ strtoupper(substr($item->nombre, 0, 2)) }}</span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold">{{ $item->nombre }} {{ $item->apellidoPaterno }}</h6>
                                <small class="text-muted">{{ $item->apellidoMaterno }}</small>
                            </div>
                            <div>
                                @if ($item->estado === 'activo')
                                    <span class="badge bg-success">Activo</span>
                                @elseif ($item->estado === 'bloqueado')
                                    <span class="badge bg-danger">Bloqueado</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-phone text-primary me-2" style="width: 20px;"></i>
                                <small>{{ $item->telefono ?: 'No registrado' }}</small>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-envelope text-primary me-2" style="width: 20px;"></i>
                                <small>{{ $item->email ?: 'No registrado' }}</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-id-card text-primary me-2" style="width: 20px;"></i>
                                <small>{{ $item->rucProveedor ?: 'Sin RUC' }}</small>
                            </div>
                        </div>

                        @if($item->puntualidad || $item->calidad || $item->precio)
                        <div class="border-top pt-3">
                            <small class="text-muted d-block mb-2">Evaluación</small>
                            <div class="row g-2 text-center">
                                <div class="col-4">
                                    <div class="eval-box">
                                        <div class="eval-value">{{ $item->puntualidad ?? '-' }}</div>
                                        <small class="text-muted">Puntual.</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="eval-box">
                                        <div class="eval-value">{{ $item->calidad ?? '-' }}</div>
                                        <small class="text-muted">Calidad</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="eval-box">
                                        <div class="eval-value">{{ $item->precio ?? '-' }}</div>
                                        <small class="text-muted">Precio</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="card-footer bg-light border-top-0">
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <a href="{{ route('proveedor.edit', $item->idProveedor) }}" 
                               class="btn btn-sm btn-outline-primary" title="Editar proveedor">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="{{ route('proveedor.dashboard', $item->idProveedor) }}" 
                               class="btn btn-sm btn-outline-info" title="Ver estadísticas">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                            <a href="{{ route('proveedor.historial', $item->idProveedor) }}" 
                               class="btn btn-sm btn-outline-secondary" title="Historial de compras">
                                <i class="fas fa-history"></i> Historial
                            </a>
                            <a href="javascript:void(0)" onclick="mostrarModalCalificar({{ $item->idProveedor }})" 
                               class="btn btn-sm btn-outline-warning" title="Calificar proveedor">
                                <i class="fas fa-star"></i> Calificar
                            </a>
                            @if ($item->estado === 'activo')
                                <form action="{{ route('proveedor.destroy', $item->idProveedor) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Desactivar este proveedor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Desactivar proveedor">
                                        <i class="fas fa-times"></i> Desactivar
                                    </button>
                                </form>

                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No se encontraron proveedores</h5>
                        <p class="text-muted mb-3">Intenta ajustar los filtros o crear un nuevo proveedor</p>
                        <a href="{{ route('proveedor.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Crear Proveedor
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    <div class="mt-4">
        {{ $proveedores->links() }}
    </div>
</div>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
}

.page-subtitle {
    opacity: 0.9;
}

.stats-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 15px 30px;
    border-radius: 10px;
    backdrop-filter: blur(10px);
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 700;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.provider-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.provider-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.provider-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.2rem;
}

.eval-box {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.eval-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #3498db;
}

.card-footer .btn-sm {
    font-size: 0.8rem;
    padding: 0.35rem 0.65rem;
    white-space: nowrap;
}

.card-footer .btn-sm i {
    margin-right: 0.4rem;
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .stats-badge {
        padding: 10px 20px;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .card-footer .btn-sm {
        font-size: 0.75rem;
        padding: 0.3rem 0.5rem;
    }
}
</style>
</div>

{{-- Modal Calificar Proveedor --}}
<div class="modal fade" id="modalCalificar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-star me-2"></i>Calificar Proveedor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCalificar" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Puntualidad</label>
                        <div class="d-flex gap-2 align-items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" 
                                           name="puntualidad" id="puntualidad{{ $i }}" 
                                           value="{{ $i }}" {{ $i == 3 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="puntualidad{{ $i }}">
                                        {{ str_repeat('⭐', $i) }}
                                    </label>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Calidad</label>
                        <div class="d-flex gap-2 align-items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" 
                                           name="calidad" id="calidad{{ $i }}" 
                                           value="{{ $i }}" {{ $i == 3 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="calidad{{ $i }}">
                                        {{ str_repeat('⭐', $i) }}
                                    </label>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Precio</label>
                        <div class="d-flex gap-2 align-items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" 
                                           name="precio" id="precio{{ $i }}" 
                                           value="{{ $i }}" {{ $i == 3 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="precio{{ $i }}">
                                        {{ str_repeat('⭐', $i) }}
                                    </label>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i> Guardar Calificación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Auto-cerrar alertas
    setTimeout(() => {
        const mensaje = document.getElementById('mensaje');
        if (mensaje) {
            const alert = bootstrap.Alert.getOrCreateInstance(mensaje);
            alert.close();
        }
    }, 4000);

    // Modal de Calificación
    function mostrarModalCalificar(proveedorId) {
        console.log('✅ Función ejecutada con ID:', proveedorId);
        
        const action = '{{ route("proveedor.calificar", ":id") }}'.replace(':id', proveedorId);
        const form = document.getElementById('formCalificar');
        
        if (!form) {
            console.error('❌ Formulario no encontrado');
            return;
        }
        
        form.action = action;
        console.log('✅ Action del form:', action);

        // Reset a valores por defecto (3 estrellas)
        ['puntualidad', 'calidad', 'precio'].forEach(nombre => {
            for (let i = 1; i <= 5; i++) {
                const radio = document.getElementById(`${nombre}${i}`);
                if (radio) radio.checked = (i === 3);
            }
        });

        // Mostrar el modal
        const modalElement = document.getElementById('modalCalificar');
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
            console.log('✅ Modal mostrado');
        } else {
            console.error('❌ Modal no encontrado');
        }
    }
</script>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
}

.page-subtitle {
    opacity: 0.9;
}

.stats-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 15px 30px;
    border-radius: 10px;
    backdrop-filter: blur(10px);
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 700;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.provider-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.provider-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.provider-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.2rem;
}

.eval-box {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.eval-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #3498db;
}

.card-footer .btn-sm {
    font-size: 0.8rem;
    padding: 0.35rem 0.65rem;
    white-space: nowrap;
}

.card-footer .btn-sm i {
    margin-right: 0.4rem;
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .stats-badge {
        padding: 10px 20px;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .card-footer .btn-sm {
        font-size: 0.75rem;
        padding: 0.3rem 0.5rem;
    }
}
</style>

@endsection