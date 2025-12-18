{{-- Modal: Agregar Insumos/Platos --}}
<div class="modal fade" id="modalAgregarInsumos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="background-color: #2d2d44; border: 1px solid #3a3a54;">
            
            {{-- Header --}}
            <div class="modal-header" style="border-bottom: 1px solid #3a3a54;">
                <h5 class="modal-title text-white">Agregar Insumos</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                
                {{-- Buscador --}}
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text" style="background-color: #3a3a54; border-color: #3a3a54; color: #fff;">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   id="buscar-plato"
                                   placeholder="Buscar plato..."
                                   style="background-color: #3a3a54; border-color: #3a3a54; color: #fff;">
                        </div>
                    </div>
                </div>

                {{-- Mostrar resultados --}}
                <div class="row mb-2">
                    <div class="col-12">
                        <small style="color: #b8b8d1;">
                            Mostrando <span id="mostrar-cantidad">0</span> registros
                        </small>
                    </div>
                </div>

                {{-- Tabla de platos disponibles --}}
                <div class="table-responsive" style="max-height: 400px;">
                    <table class="table table-dark table-hover">
                        <thead style="position: sticky; top: 0; background-color: #1a1a2e; z-index: 10;">
                            <tr>
                                <th style="color: #e0e0e0;">Nombre</th>
                                <th style="color: #e0e0e0;">Descripción</th>
                                <th style="color: #e0e0e0;">Categoría</th>
                                <th style="color: #e0e0e0;">Código</th>
                                <th style="color: #e0e0e0;">Precio</th>
                                <th width="150" style="color: #e0e0e0;">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-platos-disponibles">
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer" style="border-top: 1px solid #3a3a54;">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
console.log('✅ Script del modal cargado');
console.log('Mesa ID:', typeof mesaId !== 'undefined' ? mesaId : 'NO DEFINIDA');

// Cargar platos cuando se abre el modal
$('#modalAgregarInsumos').on('show.bs.modal', function () {
    console.log('🎭 Modal abierto, cargando platos...');
    cargarPlatosDisponibles();
});

// Búsqueda en tiempo real
let timeoutBusqueda;
$('#buscar-plato').on('keyup', function() {
    clearTimeout(timeoutBusqueda);
    timeoutBusqueda = setTimeout(() => {
        cargarPlatosDisponibles($(this).val());
    }, 500);
});

// Función para cargar platos disponibles
function cargarPlatosDisponibles(buscar = '') {
    console.log('🔍 Iniciando carga de platos...');
    const tbody = $('#tbody-platos-disponibles');
    
    // Mostrar loader
    tbody.html(`
        <tr>
            <td colspan="6" class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </td>
        </tr>
    `);

    // URL completa
    const url = '/ordenes/platos-disponibles';
    console.log('📡 URL:', url);
    console.log('📦 Datos:', { buscar: buscar, mesa_id: mesaId });

    // Petición AJAX
    $.ajax({
        url: url,
        method: 'GET',
        data: { 
            buscar: buscar,
            mesa_id: mesaId 
        },
        beforeSend: function() {
            console.log('⏳ Enviando petición AJAX...');
        },
        success: function(response) {
            console.log('✅ Respuesta recibida:', response);
            tbody.empty();

            if (!response.success) {
                console.error('❌ Error en response:', response);
                tbody.html(`
                    <tr>
                        <td colspan="6" class="text-center text-danger py-4">
                            <i class="bi bi-exclamation-triangle fs-3 d-block mb-2"></i>
                            ${response.message || 'Error desconocido'}
                        </td>
                    </tr>
                `);
                return;
            }

            if (!response.platos || response.platos.length === 0) {
                console.warn('⚠️ No hay platos');
                tbody.html(`
                    <tr>
                        <td colspan="6" class="text-center py-4" style="color: #b8b8d1;">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            No se encontraron platos disponibles
                        </td>
                    </tr>
                `);
                $('#mostrar-cantidad').text('0');
                return;
            }

            console.log(`✅ ${response.platos.length} platos encontrados`);
            $('#mostrar-cantidad').text(response.platos.length);

            // Renderizar platos
            response.platos.forEach((plato, index) => {
                console.log(`Plato ${index + 1}:`, plato);
                
                const yaAgregado = response.platos_en_orden.includes(plato.idPlatoProducto);
                
                const fila = `
                    <tr>
                        <td style="color: #e8e8f0;">${plato.nombre || 'Sin nombre'}</td>
                        <td style="color: #b8b8d1;">${plato.descripcion || 'N/A'}</td>
                        <td style="color: #b8b8d1;">${plato.categoria ? plato.categoria.nombre : 'Sin categoría'}</td>
                        <td style="color: #b8b8d1;">${plato.idPlatoProducto}</td>
                        <td style="color: #e8e8f0;">S/. ${parseFloat(plato.precio).toFixed(2)}</td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-agregar-plato" 
                                    data-plato-id="${plato.idPlatoProducto}"
                                    ${yaAgregado ? 'disabled' : ''}
                                    style="background-color: ${yaAgregado ? '#6c757d' : '#ffc107'}; border-color: ${yaAgregado ? '#6c757d' : '#ffc107'}; color: #000;">
                                <i class="bi bi-cart-plus me-1"></i>
                                ${yaAgregado ? 'Agregado' : 'Agregar'}
                            </button>
                        </td>
                    </tr>
                `;
                
                tbody.append(fila);
            });
            
            console.log('✅ Platos renderizados correctamente');
        },
        error: function(xhr, status, error) {
            console.error('❌ ERROR AJAX:', {
                status: xhr.status,
                statusText: xhr.statusText,
                error: error,
                responseText: xhr.responseText
            });
            
            tbody.html(`
                <tr>
                    <td colspan="6" class="text-center text-danger py-4">
                        <i class="bi bi-exclamation-triangle fs-3 d-block mb-2"></i>
                        Error al cargar platos
                        <br><small>Status: ${xhr.status} - ${error}</small>
                        <br><small class="text-warning">Revisa la consola (F12) para más detalles</small>
                    </td>
                </tr>
            `);
        }
    });
}

// Agregar plato a la orden
$(document).on('click', '.btn-agregar-plato', function() {
    const btn = $(this);
    const platoId = btn.data('plato-id');
    
    console.log('➕ Agregando plato:', platoId);
    
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Agregando...');

    $.ajax({
        url: "/ordenes/mesa/{{ $mesa->id }}/agregar-plato",
        method: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            plato_id: platoId
        },
        success: function(response) {
            console.log('✅ Plato agregado:', response);
            mostrarAlerta('success', response.message);
            
            agregarFilaPlato(response.plato);
            
            $('#total-orden').text('S/. ' + parseFloat(response.total).toFixed(2));
            $('#cantidad-items').text(parseInt($('#cantidad-items').text()) + 1);
            $('#btn-cobrar').prop('disabled', false);
            
            btn.html('<i class="bi bi-check-circle me-1"></i>Agregado')
               .css('background-color', '#6c757d');
            
            $('#fila-vacia').remove();
        },
        error: function(xhr) {
            console.error('❌ Error al agregar:', xhr);
            const mensaje = xhr.responseJSON?.message || 'Error al agregar plato';
            mostrarAlerta('danger', mensaje);
            btn.prop('disabled', false).html('<i class="bi bi-cart-plus me-1"></i>Agregar');
        }
    });
});

// Función para agregar fila a la tabla
function agregarFilaPlato(plato) {
    const subtotal = (plato.precio * plato.cantidad).toFixed(2);
    
    const fila = `
        <tr data-plato-id="${plato.id}">
            <td style="color: #e8e8f0;">${plato.nombre}</td>
            <td style="color: #e8e8f0;">S/. ${parseFloat(plato.precio).toFixed(2)}</td>
            <td>
                <div class="input-group input-group-sm">
                    <button class="btn btn-cantidad-menos" type="button" style="background-color: #dc3545; border-color: #dc3545; color: #fff;">-</button>
                    <input type="number" class="form-control text-center input-cantidad" value="${plato.cantidad}" min="1" max="50" style="background-color: #3a3a54; color: #fff; border-color: #3a3a54;">
                    <button class="btn btn-cantidad-mas" type="button" style="background-color: #28a745; border-color: #28a745; color: #fff;">+</button>
                </div>
            </td>
            <td class="subtotal" style="color: #e8e8f0;">S/. ${subtotal}</td>
            <td>
                <button class="btn btn-sm btn-nota" style="background-color: #17a2b8; border-color: #17a2b8; color: #fff;" title="Agregar nota">
                    <i class="bi bi-pencil-square"></i>
                </button>
            </td>
            <td>
                <button class="btn btn-sm btn-eliminar" style="background-color: #dc3545; border-color: #dc3545; color: #fff;">
                    <i class="bi bi-x-circle"></i>
                </button>
            </td>
        </tr>
    `;
    
    $('#tbody-platos').append(fila);
}

// Mostrar alertas
function mostrarAlerta(tipo, mensaje) {
    const alerta = `
        <div class="alert alert-${tipo} alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 9999;">
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    $('body').append(alerta);
    
    setTimeout(() => {
        $('.alert').fadeOut(() => $(this).remove());
    }, 3000);
}
</script>
@endpush