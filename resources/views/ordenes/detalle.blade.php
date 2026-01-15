{{-- ordenes/detalle.blade.php--}}
@extends('layouts.plantilla')

@section('title', 'Mesa #' . $mesa->numero . ' - Detalle de Orden')

@section('contenido')
<div class="container-fluid py-4" style="background-color: #f8f9fa; min-height: 100vh;">
    
    {{-- Header con título --}}
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-dark mb-2">
                    Mesa #{{ $mesa->numero }}
                    
                    @if($esReserva ?? false)
                        <span class="badge ms-2" style="background: linear-gradient(135deg, #ffc107 0%, #17a2b8 100%); color: #000; font-size: 0.7rem;">
                            <i class="bi bi-calendar-check me-1"></i>RESERVA
                        </span>
                    @endif
                </h2>
                <p class="text-muted">Gestiona los platos de esta orden</p>
            </div>
            
            @if($esReserva && isset($reserva))
                <div class="alert alert-info mb-0 py-2" style="background-color: rgba(13, 202, 240, 0.15); border-color: #0dcaf0;">
                    <small style="color: #055160;">
                        <i class="bi bi-person-circle me-1"></i>
                        <strong>{{ $reserva->nombre_cliente }}</strong><br>
                        <i class="bi bi-clock me-1"></i>
                        Reserva: {{ \Carbon\Carbon::parse($reserva->hora_reserva)->format('g:i A') }}
                    </small>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        {{-- Columna izquierda: Tabla de platos --}}
        <div class="col-lg-8 col-md-7 mb-4">
            <div class="card shadow-sm" style="background-color: #ffffff; border: 1px solid #e0e0e0; border-radius: 12px;">
                <div class="card-body">
                    
                    @if($esReserva ?? false)
                        <div class="alert alert-success mb-3" style="background-color: rgba(25, 135, 84, 0.15); border-color: #28a745; color: #0f5132;">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>Platos pre-ordenados cargados automáticamente</strong>
                            <p class="mb-0 small">Estos platos fueron seleccionados al hacer la reserva. Puedes agregar más o modificar cantidades.</p>
                        </div>
                    @endif
                    
                    {{-- Tabla de platos --}}
                    <div class="table-responsive">
                        <table class="table table-hover" id="tabla-orden">
                            <thead style="background-color: #f1f3f5;">
                                <tr>
                                    <th style="color: #495057;">Insumo</th>
                                    <th width="100" style="color: #495057;">Precio</th>
                                    <th width="120" style="color: #495057;">Cantidad</th>
                                    <th width="100" style="color: #495057;">Subtotal</th>
                                    <th width="150" style="color: #495057;">Nota</th>
                                    <th width="80" style="color: #495057;">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-platos">
                                @forelse($orden->platos ?? [] as $ordenPlato)
                                    <tr data-plato-id="{{ $ordenPlato->id }}">
                                        <td style="color: #212529;">
                                            {{ $ordenPlato->plato->nombre ?? 'Plato eliminado' }}
                                            {{-- ✅ CORRECCIÓN: Usar es_preorden del plato, NO $esReserva de la orden --}}
                                            @if($ordenPlato->es_preorden ?? false)
                                                <span class="badge badge-sm ms-2" style="background-color: #28a745; color: #fff; font-size: 0.65rem;">
                                                    <i class="bi bi-calendar-check"></i> Pre-orden
                                                </span>
                                            @endif
                                        </td>
                                        <td style="color: #212529;">${{ number_format($ordenPlato->precio_unitario, 2) }}</td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <button class="btn btn-outline-secondary btn-cantidad-menos" type="button" style="background-color: #dc3545; border-color: #dc3545; color: #fff;">
                                                    -
                                                </button>
                                                <input type="number" 
                                                       class="form-control text-center input-cantidad" 
                                                       value="{{ $ordenPlato->cantidad }}" 
                                                       min="1" 
                                                       max="50"
                                                       style="background-color: #ffffff; color: #212529; border-color: #ced4da;">
                                                <button class="btn btn-outline-secondary btn-cantidad-mas" type="button" style="background-color: #28a745; border-color: #28a745; color: #fff;">
                                                    +
                                                </button>
                                            </div>
                                        </td>
                                        <td class="subtotal" style="color: #212529; font-weight: 600;">
                                            ${{ number_format($ordenPlato->subtotal, 2) }}
                                        </td>
                                        <td class="td-nota">
                                            <button class="btn btn-sm btn-outline-info btn-nota" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Agregar nota"
                                                    style="background-color: #0dcaf0; border-color: #0dcaf0; color: #000;">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            @if($ordenPlato->notas)
                                                <small class="text-info d-block mt-1" 
                                                       data-nota-completa="{{ $ordenPlato->notas }}" 
                                                       style="cursor: help; color: #055160;" 
                                                       title="{{ $ordenPlato->notas }}">
                                                    {{ Str::limit($ordenPlato->notas, 30) }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-eliminar" 
                                                    style="background-color: #dc3545; border-color: #dc3545; color: #fff;">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="fila-vacia">
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                            No hay platos en esta orden. Agrega platos usando el botón de abajo.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="d-flex gap-2 mt-3">
                        <button type="button" 
                                class="btn btn-primary" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalAgregarInsumos">
                            <i class="bi bi-plus-circle me-2"></i>Agregar Insumos
                        </button>
                        
                        <a href="{{ route('ordenes.index') }}" 
                           class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Volver
                        </a>

                        <button type="button" 
                                class="btn btn-danger"
                                onclick="confirmarCancelar()"
                                data-url="{{ route('ordenes.cancelar', $mesa->id) }}">
                            <i class="bi bi-x-circle me-2"></i>Cancelar Orden
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- Columna derecha: Total y cobrar --}}
        <div class="col-lg-4 col-md-5">
            <div class="card shadow-sm" style="background-color: #ffffff; border: 1px solid #e0e0e0; border-radius: 12px;">
                <div class="card-body text-center">
                    <h5 class="text-dark mb-3">Total:</h5>
                    <h2 class="text-success mb-4 fw-bold" id="total-orden">
                        S/. {{ number_format($total, 2) }}
                    </h2>

                    <button type="button" 
                            class="btn btn-success btn-lg w-100"
                            id="btn-cobrar"
                            onclick="abrirModalPago({{ $mesa->id }}, {{ $total }})"
                            {{ $orden->platos->isEmpty() ? 'disabled' : '' }}>
                        <i class="bi bi-cash-coin me-2"></i>Cobrar
                    </button>

                    <div class="mt-4 text-start">
                        <p class="small mb-1 text-muted">
                            <i class="bi bi-receipt me-2"></i>Items: <span id="cantidad-items">{{ $orden->platos->count() }}</span>
                        </p>
                        <p class="small mb-0 text-muted">
                            <i class="bi bi-clock me-2"></i>Apertura: {{ $orden->fecha_apertura->format('h:i A') }}
                        </p>
                        
                        @if($esReserva ?? false)
                            <p class="small mb-0 mt-2" style="color: #0dcaf0;">
                                <i class="bi bi-calendar-check me-2"></i>
                                <strong>Orden de Reserva</strong>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Modal: Agregar Insumos --}}
@include('ordenes.partials.modal-agregar-insumos')

{{-- Modal: Agregar/Editar Nota --}}
@include('ordenes.partials.modal-nota')

{{-- Modal: Pago --}}
@include('ordenes.partials.modal-pago')

@endsection

@push('styles')
<style>
    body {
        background-color: #f8f9fa !important;
    }
    
    .table {
        --bs-table-bg: #ffffff;
        --bs-table-hover-bg: #f8f9fa;
        color: #212529;
    }
    
    .table thead {
        border-bottom: 2px solid #dee2e6;
    }
    
    .table tbody tr {
        border-bottom: 1px solid #e9ecef;
    }
    
    .btn:hover {
        opacity: 0.9;
    }
    
    .input-cantidad::-webkit-inner-spin-button,
    .input-cantidad::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .badge[style*="linear-gradient"] {
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
    
    .card {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
    }
    
    .card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        transition: box-shadow 0.3s ease;
    }
</style>
@endpush

@push('scripts')
<script>
    const mesaId = {{ $mesa->id }};
    const urlActualizarCantidad = "{{ route('ordenes.actualizar_cantidad', $mesa->id) }}";
    const urlEliminarPlato = "{{ route('ordenes.eliminar_plato', ['mesa' => $mesa->id, 'plato' => ':plato']) }}";
    const urlActualizarNota = "{{ route('ordenes.actualizar_nota', $mesa->id) }}";

    function confirmarCancelar() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = event.target.dataset.url;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        if (confirm('¿Estás seguro de cancelar esta orden? Se liberará la mesa y se perderán todos los platos agregados.')) {
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
<script src="{{ asset('js/ordenes.js') }}"></script>
@endpush