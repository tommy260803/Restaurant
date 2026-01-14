{{-- filepath: resources/views/compras/edit.blade.php --}}
@extends('layouts.plantilla')

@section('contenido')
<div class="container-fluid py-4">
    
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-pencil-square text-primary me-2"></i>
                Editar Compra #{{ $compra->idCompra }}
            </h1>
            <p class="text-muted mb-0">Modifica los datos de la compra y gestiona sus ingredientes</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('compras.show', $compra->idCompra) }}" class="btn btn-info rounded-2 shadow-sm px-4">
                <i class="bi bi-eye me-2"></i>Ver Detalles
            </a>
            <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary rounded-2 px-4">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Formulario Principal --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-info-circle me-2 text-primary"></i>Información General
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('compras.update', $compra->idCompra) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="idProveedor" class="form-label small text-muted">
                            <i class="bi bi-person me-1"></i>Proveedor <span class="text-danger">*</span>
                        </label>
                        <select name="idProveedor" id="idProveedor" class="form-select @error('idProveedor') is-invalid @enderror" required>
                            <option value="">-- Seleccione un proveedor --</option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->idProveedor }}" {{ $compra->idProveedor == $proveedor->idProveedor ? 'selected' : '' }}>
                                    {{ $proveedor->nombre }} {{ $proveedor->apellidoPaterno }} - RUC: {{ $proveedor->rucProveedor ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        @error('idProveedor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="fecha" class="form-label small text-muted">
                            <i class="bi bi-calendar-check me-1"></i>Fecha <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="fecha" id="fecha" class="form-control @error('fecha') is-invalid @enderror" value="{{ $compra->fecha }}" required>
                        @error('fecha')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="estado" class="form-label small text-muted">
                            <i class="bi bi-flag me-1"></i>Estado
                        </label>
                        <select name="estado" id="estado" class="form-select">
                            <option value="pendiente" {{ $compra->estado == 'pendiente' ? 'selected' : '' }}>
                                <i class="bi bi-hourglass-split"></i> Pendiente
                            </option>
                            <option value="en_transito" {{ $compra->estado == 'en_transito' ? 'selected' : '' }}>
                                <i class="bi bi-truck"></i> En Tránsito
                            </option>
                            <option value="recibida" {{ $compra->estado == 'recibida' ? 'selected' : '' }}>
                                <i class="bi bi-check-circle"></i> Recibida
                            </option>
                            <option value="anulada" {{ $compra->estado == 'anulada' ? 'selected' : '' }}>
                                <i class="bi bi-x-circle"></i> Anulada
                            </option>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <label for="descripcion" class="form-label small text-muted">
                        <i class="bi bi-card-text me-1"></i>Descripción <span class="text-danger">*</span>
                    </label>
                    <textarea name="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3" required>{{ $compra->descripcion }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end pt-3 border-top mt-4">
                    <button type="submit" class="btn btn-success rounded-2 px-4 shadow-sm">
                        <i class="bi bi-check-circle me-2"></i>Actualizar Compra
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Detalles de la Compra --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient text-white border-0 py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h5 class="mb-0">
                <i class="bi bi-list-check me-2"></i>Detalles de la Compra
            </h5>
        </div>
        <div class="card-body p-0">
            @if($compra->detalles->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 ps-4">
                                    <i class="bi bi-box me-1"></i>Ingrediente
                                </th>
                                <th class="text-center">
                                    <i class="bi bi-hash me-1"></i>Cantidad
                                </th>
                                <th class="text-end">
                                    <i class="bi bi-tag me-1"></i>Precio Unit.
                                </th>
                                <th class="text-end">
                                    <i class="bi bi-cash me-1"></i>Subtotal
                                </th>
                                <th class="text-center" width="100">
                                    <i class="bi bi-gear me-1"></i>Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compra->detalles as $detalle)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                            <i class="bi bi-box text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $detalle->ingrediente->nombre ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $detalle->ingrediente->categoria ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark">{{ $detalle->cantidad }}</span>
                                </td>
                                <td class="text-end">S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
                                <td class="text-end fw-bold text-success">S/ {{ number_format($detalle->subtotal, 2) }}</td>
                                <td class="text-center">
                                    <form action="{{ route('detalle-compra.destroy', $detalle->idDetalleCompra) }}" method="POST" style="display:inline-block;" onsubmit="return confirmarEliminar(event);">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="3" class="text-end fw-bold py-3 ps-4">TOTAL:</td>
                                <td class="text-end fw-bold text-success fs-5">S/ {{ number_format($compra->total, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="text-muted">
                        <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.2;"></i>
                        <h5 class="mt-3 mb-2">No hay ingredientes agregados</h5>
                        <p class="mb-3">Agrega uno o más ingredientes usando el formulario abajo</p>
                    </div>
                </div>
            @endif
        </div>
        
        {{-- Formulario para Agregar Ingrediente --}}
        <div class="card-footer bg-light border-0 py-4">
            <h6 class="mb-3">
                <i class="bi bi-plus-circle me-2 text-success"></i>Agregar Nuevo Ingrediente
            </h6>
            <form method="POST" action="{{ route('detalle-compra.store') }}" id="formAgregarDetalle">
                @csrf
                <input type="hidden" name="idCompra" value="{{ $compra->idCompra }}">
                
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label small text-muted">
                            <i class="bi bi-box me-1"></i>Ingrediente <span class="text-danger">*</span>
                        </label>
                        <select name="idIngrediente" id="selectIngrediente" class="form-select" required onchange="mostrarDetalleIngrediente()">
                            <option value="">-- Selecciona un ingrediente --</option>
                            @foreach($ingredientes as $ingrediente)
                                <option value="{{ $ingrediente->id }}" 
                                        data-nombre="{{ $ingrediente->nombre }}"
                                        data-stock="{{ $ingrediente->stock ?? 0 }}"
                                        data-categoria="{{ $ingrediente->categoria ?? 'N/A' }}"
                                        data-unidad="{{ $ingrediente->unidad ?? 'kg' }}">
                                    {{ $ingrediente->nombre }} (Stock: {{ $ingrediente->stock ?? '0' }} {{ $ingrediente->unidad ?? 'kg' }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Puedes agregar varios ingredientes a esta compra</small>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small text-muted">
                            <i class="bi bi-hash me-1"></i>Cantidad <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="cantidad" id="inputCantidad" step="0.01" min="0.01" class="form-control" placeholder="100" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small text-muted">
                            <i class="bi bi-tag me-1"></i>Precio Unit. <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">S/</span>
                            <input type="number" name="precio_unitario" id="inputPrecio" step="0.01" min="0.01" class="form-control" placeholder="15.50" required>
                        </div>
                    </div>

                    <div class="col-md-2 d-flex align-items-center mt-4">
                        <button type="submit" class="btn btn-success w-100 rounded-2 shadow-sm">
                            <i class="bi bi-plus-lg me-1"></i>Agregar
                        </button>
                    </div>
                </div>
            </form>

            {{-- Información del ingrediente seleccionado --}}
            <div id="detalleIngrediente" class="mt-4" style="display: none;">
                <div class="card border-info shadow-sm">
                    <div class="card-header bg-info bg-opacity-10 border-info py-2">
                        <h6 class="mb-0 text-info small">
                            <i class="bi bi-info-circle me-2"></i>Información del Ingrediente
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-1">Nombre</label>
                                <p id="detNombre" class="fw-semibold mb-0 small">-</p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-1">Stock Actual</label>
                                <p id="detStock" class="fw-semibold mb-0 small">-</p>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small text-muted mb-1">Categoría</label>
                                <p id="detCategoria" class="fw-semibold mb-0 small">-</p>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small text-muted mb-1">Unidad</label>
                                <p id="detUnidad" class="fw-semibold mb-0 small">-</p>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small text-muted mb-1">Subtotal</label>
                                <p id="detSubtotal" class="fw-bold text-success mb-0">S/ -</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Resumen --}}
            @if($compra->detalles->count() > 0)
            <div class="mt-3">
                <div class="alert alert-success border-success mb-0 d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-5 me-3"></i>
                    <div>
                        <strong>{{ $compra->detalles->count() }}</strong> ingrediente(s) guardado(s) en esta compra
                    </div>
                </div>
            </div>
            @endif

            <div class="alert alert-info border-info mt-3 mb-0">
                <i class="bi bi-lightbulb me-2"></i>
                <strong>Consejo:</strong> Cada ingrediente que agregues se guardará inmediatamente. Puedes agregar varios ingredientes uno por uno.
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Asegurar que Bootstrap Icons se cargue */
    @import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css');
    
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
    }
    
    /* Cards mejorados */
    .card {
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    /* Tabla mejorada */
    .table-hover tbody tr {
        transition: all 0.2s ease;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
        transform: scale(1.002);
    }
    
    /* Botones mejorados */
    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
    }
    
    /* Avatar */
    .avatar-sm {
        font-size: 1rem;
    }
    
    /* Input focus mejorado */
    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
    }
    
    /* Animaciones */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .card {
        animation: fadeIn 0.3s ease;
    }
    
    /* Mejorar apariencia de badges */
    .badge {
        font-weight: 500;
        padding: 0.5rem 0.75rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .d-flex.gap-2 {
            flex-direction: column;
        }
        
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
function mostrarDetalleIngrediente() {
    const selectIngrediente = document.getElementById('selectIngrediente');
    const detalleDiv = document.getElementById('detalleIngrediente');
    const selectedOption = selectIngrediente.options[selectIngrediente.selectedIndex];
    
    if (selectIngrediente.value === '') {
        detalleDiv.style.display = 'none';
        return;
    }

    // Obtener datos del option
    const nombre = selectedOption.getAttribute('data-nombre');
    const stock = selectedOption.getAttribute('data-stock');
    const categoria = selectedOption.getAttribute('data-categoria');
    const unidad = selectedOption.getAttribute('data-unidad');
    
    // Actualizar información
    document.getElementById('detNombre').textContent = nombre;
    document.getElementById('detStock').textContent = stock + ' ' + unidad;
    document.getElementById('detCategoria').textContent = categoria;
    document.getElementById('detUnidad').textContent = unidad;
    
    // Actualizar cantidad y subtotal en tiempo real
    actualizarSubtotal();
    
    // Mostrar detalle con animación suave
    detalleDiv.style.display = 'block';
}

function actualizarSubtotal() {
    const cantidad = parseFloat(document.getElementById('inputCantidad').value) || 0;
    const precio = parseFloat(document.getElementById('inputPrecio').value) || 0;
    const subtotal = cantidad * precio;
    
    document.getElementById('detSubtotal').textContent = subtotal > 0 ? 'S/ ' + subtotal.toFixed(2) : 'S/ -';
}

function confirmarEliminar(event) {
    event.preventDefault();
    
    Swal.fire({
        title: '¿Eliminar este ingrediente?',
        text: "Se eliminará de la compra",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: {
            popup: 'rounded-4 shadow',
            confirmButton: 'btn btn-danger rounded-pill px-4',
            cancelButton: 'btn btn-secondary rounded-pill px-4'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            event.target.submit();
        }
    });
    
    return false;
}

// Escuchar cambios en cantidad y precio
document.getElementById('inputCantidad').addEventListener('input', actualizarSubtotal);
document.getElementById('inputPrecio').addEventListener('input', actualizarSubtotal);
</script>
@endpush