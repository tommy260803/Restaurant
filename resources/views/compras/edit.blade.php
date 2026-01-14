{{-- filepath: resources/views/compras/edit.blade.php --}}
@extends('layouts.plantilla')
@section('contenido')
<div class="container mt-4">
    <h1 class="mb-4">Editar Compra #{{ $compra->idCompra }}</h1>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('compras.update', $compra->idCompra) }}">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="idProveedor" class="form-label">Proveedor <span class="text-danger">*</span></label>
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
            <div class="col-md-3 mb-3">
                <label for="fecha" class="form-label">Fecha <span class="text-danger">*</span></label>
                <input type="date" name="fecha" id="fecha" class="form-control @error('fecha') is-invalid @enderror" value="{{ $compra->fecha }}" required>
                @error('fecha')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3 mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="pendiente" {{ $compra->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en_transito" {{ $compra->estado == 'en_transito' ? 'selected' : '' }}>En Tránsito</option>
                    <option value="recibida" {{ $compra->estado == 'recibida' ? 'selected' : '' }}>Recibida</option>
                    <option value="anulada" {{ $compra->estado == 'anulada' ? 'selected' : '' }}>Anulada</option>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
            <textarea name="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3" required>{{ $compra->descripcion }}</textarea>
            @error('descripcion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between mb-4">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-1"></i> Actualizar Compra
            </button>
            <div>
                <a href="{{ route('compras.show', $compra->idCompra) }}" class="btn btn-info">
                    <i class="fas fa-eye me-1"></i> Ver Detalles
                </a>
                <a href="{{ route('compras.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>
    </form>

    <hr>

    <div class="card\">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Detalles de la Compra</h5>
        </div>
        <div class="card-body">
            @if($compra->detalles->count() > 0)
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Ingrediente</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Subtotal</th>
                                <th width="80">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compra->detalles as $detalle)
                            <tr>
                                <td>
                                    <strong>{{ $detalle->ingrediente->nombre ?? 'N/A' }}</strong>
                                </td>
                                <td>{{ $detalle->cantidad }}</td>
                                <td>S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
                                <td>S/ {{ number_format($detalle->subtotal, 2) }}</td>
                                <td>
                                    <form action="{{ route('detalle-compra.destroy', $detalle->idDetalleCompra) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar este ingrediente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td colspan="3" class="text-end">TOTAL:</td>
                                <td>S/ {{ number_format($compra->total, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <p class="text-muted mb-4"><i class="fas fa-info-circle me-2"></i>No hay ingredientes agregados aún. Agrega uno o más ingredientes usando el formulario abajo.</p>
            @endif

            <hr>

            <h6 class="mb-3"><i class="fas fa-plus-circle me-2"></i>Agregar Nuevo Ingrediente a esta Compra</h6>
            <form method="POST" action="{{ route('detalle-compra.store') }}" id="formAgregarDetalle" class="row g-3">
                @csrf
                <input type="hidden" name="idCompra" value="{{ $compra->idCompra }}">
                
                <div class="col-md-5">
                    <label class="form-label"><strong>Ingrediente</strong> <span class="text-danger">*</span></label>
                    <select name="idIngrediente" id="selectIngrediente" class="form-select" required onchange="mostrarDetalleIngrediente()">
                        <option value="">-- Selecciona un ingrediente --</option>
                        @foreach($ingredientes as $ingrediente)
                            <option value="{{ $ingrediente->id }}" 
                                    data-nombre="{{ $ingrediente->nombre }}"
                                    data-stock="{{ $ingrediente->stock ?? 0 }}"
                                    data-categoria="{{ $ingrediente->categoria ?? 'N/A' }}"
                                    data-unidad="{{ $ingrediente->unidad ?? 'kg' }}">
                                [ID:{{ $ingrediente->id }}] {{ $ingrediente->nombre }} (Stock: {{ $ingrediente->stock ?? '0' }} {{ $ingrediente->unidad ?? 'kg' }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Puedes agregar varios ingredientes diferentes a esta compra</small>
                </div>

                <div class="col-md-2">
                    <label class="form-label"><strong>Cantidad</strong> <span class="text-danger">*</span></label>
                    <input type="number" name="cantidad" id="inputCantidad" step="0.01" min="0.01" class="form-control" placeholder="Ej: 100" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label"><strong>Precio Unit.</strong> <span class="text-danger">*</span></label>
                    <input type="number" name="precio_unitario" id="inputPrecio" step="0.01" min="0.01" class="form-control" placeholder="Ej: 15.50" required>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-plus me-2"></i> Agregar Ingrediente
                    </button>
                </div>
            </form>

            <!-- Información del ingrediente seleccionado -->
            <div id="detalleIngrediente" class="mt-4" style="display: none;">
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-box me-2"></i>Información del Ingrediente Seleccionado</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Nombre</label>
                                <p id="detNombre" class="fw-bold mb-0">-</p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Stock Actual</label>
                                <p id="detStock" class="fw-bold mb-0">-</p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Categoría</label>
                                <p id="detCategoria" class="fw-bold mb-0">-</p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Unidad de Medida</label>
                                <p id="detUnidad" class="fw-bold mb-0">-</p>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Cantidad a Comprar</label>
                                <p id="detCantidad" class="fw-bold mb-0">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Subtotal Estimado</label>
                                <p id="detSubtotal" class="fw-bold text-success mb-0">S/ -</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen de ingredientes ya guardados -->
            @if($compra->detalles->count() > 0)
            <div class="mt-4">
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Ingredientes guardados:</strong> {{ $compra->detalles->count() }}
                    <a href="{{ route('compras.show', $compra->idCompra) }}" class="alert-link ms-2">Ver todos</a>
                </div>
            </div>
            @endif

            <div class="alert alert-info mt-3 small">
                <i class="fas fa-lightbulb me-2"></i>
                <strong>Consejo:</strong> Cada vez que agregues un ingrediente se guardará inmediatamente. Puedes agregar varios ingredientes uno por uno.
            </div>
        </div>
    </div>

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
        
        // Mostrar detalle
        detalleDiv.style.display = 'block';
    }

    function actualizarSubtotal() {
        const cantidad = parseFloat(document.getElementById('inputCantidad').value) || 0;
        const precio = parseFloat(document.getElementById('inputPrecio').value) || 0;
        const subtotal = cantidad * precio;
        
        document.getElementById('detCantidad').textContent = cantidad > 0 ? cantidad : '-';
        document.getElementById('detSubtotal').textContent = subtotal > 0 ? 'S/ ' + subtotal.toFixed(2) : 'S/ -';
    }

    // Escuchar cambios en cantidad y precio
    document.getElementById('inputCantidad').addEventListener('input', actualizarSubtotal);
    document.getElementById('inputPrecio').addEventListener('input', actualizarSubtotal);
</script>
</div>
@endsection