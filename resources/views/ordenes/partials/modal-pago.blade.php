{{-- ordenes/partials/modal-pago.blade.php--}}
{{-- Modal: Formulario de Pago con Cliente --}}
<div class="modal fade" id="modalPago" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color: #2d2d44; border: 1px solid #3a3a54;">
            
            {{-- Header --}}
            <div class="modal-header" style="border-bottom: 1px solid #3a3a54;">
                <h5 class="modal-title text-white">
                    <i class="bi bi-cash-coin me-2"></i>Procesar Pago
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                <form id="form-pago">
                    @csrf
                    
                    {{-- Información de la orden --}}
                    <div class="alert alert-info mb-4" style="background-color: rgba(23, 162, 184, 0.2); border-color: #17a2b8;">
                        <strong>Total a Pagar:</strong>
                        <h4 class="text-success mt-2" id="monto-pago">S/. 0.00</h4>
                    </div>

                    {{-- Datos del Cliente --}}
                    <h6 class="text-white mb-3">
                        <i class="bi bi-person-circle me-2"></i>Datos del Cliente
                    </h6>

                    {{-- Búsqueda/Selección de cliente --}}
                    <div class="mb-3">
                        <label class="form-label text-white">Cliente *</label>
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   id="buscar-cliente" 
                                   placeholder="Buscar cliente por nombre..."
                                   style="background-color: #3a3a54; border-color: #3a3a54; color: #fff;">
                            <button type="button" class="btn btn-outline-secondary" id="btn-nuevo-cliente">
                                <i class="bi bi-plus-circle me-1"></i>Nuevo
                            </button>
                        </div>
                        <div id="lista-clientes" class="position-absolute mt-1 w-100" style="background-color: #3a3a54; border: 1px solid #3a3a54; max-height: 200px; overflow-y: auto; display: none; z-index: 1000;"></div>
                        <input type="hidden" id="cliente_id" name="cliente_id">
                    </div>

                    {{-- Datos Cliente Seleccionado --}}
                    <div id="cliente-info" class="mb-3 p-3" style="display: none; background-color: #3a3a54; border-radius: 5px;">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong style="color: #b8b8d1;">Nombre:</strong></p>
                                <p class="text-white" id="cliente-nombre">-</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong style="color: #b8b8d1;">Teléfono:</strong></p>
                                <p class="text-white" id="cliente-telefono">-</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong style="color: #b8b8d1;">Email:</strong></p>
                                <p class="text-white" id="cliente-email">-</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong style="color: #b8b8d1;">Puntos:</strong></p>
                                <p class="text-success" id="cliente-puntos">0</p>
                            </div>
                        </div>
                    </div>

                    {{-- O Datos rápidos si no hay cliente --}}
                    <div id="datos-rapidos" class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sin-cliente">
                            <label class="form-check-label text-white" for="sin-cliente">
                                Venta sin cliente registrado
                            </label>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <hr style="border-color: #3a3a54;">

                    {{-- Método de Pago --}}
                    <h6 class="text-white mb-3">
                        <i class="bi bi-credit-card me-2"></i>Método de Pago *
                    </h6>

                    <div class="mb-3">
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="metodo" id="metodo-efectivo" value="efectivo" checked>
                            <label class="btn btn-outline-success" for="metodo-efectivo">
                                <i class="bi bi-cash-coin me-1"></i>Efectivo
                            </label>

                            <input type="radio" class="btn-check" name="metodo" id="metodo-tarjeta" value="tarjeta">
                            <label class="btn btn-outline-success" for="metodo-tarjeta">
                                <i class="bi bi-credit-card me-1"></i>Tarjeta
                            </label>

                            <input type="radio" class="btn-check" name="metodo" id="metodo-yape" value="yape">
                            <label class="btn btn-outline-success" for="metodo-yape">
                                <i class="bi bi-wallet2 me-1"></i>Yape
                            </label>

                            <input type="radio" class="btn-check" name="metodo" id="metodo-plin" value="plin">
                            <label class="btn btn-outline-success" for="metodo-plin">
                                <i class="bi bi-wallet2 me-1"></i>Plin
                            </label>

                            <input type="radio" class="btn-check" name="metodo" id="metodo-otros" value="otros">
                            <label class="btn btn-outline-success" for="metodo-otros">
                                <i class="bi bi-question-circle me-1"></i>Otros
                            </label>
                        </div>
                    </div>

                    {{-- Número de operación (para pagos digitales) --}}
                    <div class="mb-3" id="operacion-container" style="display: none;">
                        <label class="form-label text-white">Número de Operación</label>
                        <input type="text" 
                               class="form-control" 
                               id="numero_operacion" 
                               name="numero_operacion"
                               placeholder="Ej: 12345678"
                               style="background-color: #3a3a54; border-color: #3a3a54; color: #fff;">
                    </div>

                </form>
            </div>

            {{-- Footer --}}
            <div class="modal-footer" style="border-top: 1px solid #3a3a54;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" class="btn btn-success btn-lg" id="btn-confirmar-pago">
                    <i class="bi bi-check-circle me-1"></i>Procesar Pago
                </button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    let clienteSeleccionado = null;
    let mesaIdPago = null;
    let montoPago = 0;

    // Abrir modal de pago
    function abrirModalPago(mesaId, total) {
        mesaIdPago = mesaId;
        montoPago = total;
        $('#monto-pago').text('S/. ' + parseFloat(total).toFixed(2));
        $('#cliente_id').val('');
        $('#buscar-cliente').val('');
        $('#cliente-info').hide();
        clienteSeleccionado = null;
        $('#sin-cliente').prop('checked', false);
        $('#metodo-efectivo').prop('checked', true);
        $('#operacion-container').hide();
        $('#form-pago')[0].reset();
        $('#modalPago').modal('show');
    }

    // Buscar clientes en tiempo real
    $('#buscar-cliente').on('keyup', function() {
        const buscar = $(this).val().trim();
        
        if (buscar.length < 2) {
            $('#lista-clientes').hide();
            return;
        }

        $.ajax({
            url: '/ordenes/buscar-clientes',
            method: 'GET',
            data: { buscar: buscar },
            success: function(response) {
                const lista = $('#lista-clientes');
                lista.empty();
                
                if (response.clientes.length === 0) {
                    lista.html('<div class="p-2" style="color: #b8b8d1;">No hay resultados</div>');
                } else {
                    response.clientes.forEach(cliente => {
                        lista.append(`
                            <div class="p-2" style="cursor: pointer; border-bottom: 1px solid #2d2d44; color: #fff;" 
                                 onclick="seleccionarCliente(${cliente.idCliente}, '${cliente.nombre}', '${cliente.telefono}', '${cliente.email}', ${cliente.puntos})">
                                <strong>${cliente.nombre}</strong><br>
                                <small style="color: #b8b8d1;">${cliente.telefono || 'Sin teléfono'} | ${cliente.email || 'Sin email'}</small>
                            </div>
                        `);
                    });
                }
                lista.show();
            }
        });
    });

    // Seleccionar cliente
    function seleccionarCliente(id, nombre, telefono, email, puntos) {
        clienteSeleccionado = { id, nombre, telefono, email, puntos };
        $('#cliente_id').val(id);
        $('#buscar-cliente').val(nombre);
        $('#cliente-nombre').text(nombre);
        $('#cliente-telefono').text(telefono || 'No registrado');
        $('#cliente-email').text(email || 'No registrado');
        $('#cliente-puntos').text(puntos || '0');
        $('#cliente-info').show();
        $('#lista-clientes').hide();
    }

    // Nuevo cliente
    $('#btn-nuevo-cliente').on('click', function() {
        // Aquí podrías abrir un modal para crear un cliente rápido
        alert('Función de crear cliente - Por implementar');
    });

    // Checkbox sin cliente
    $('#sin-cliente').on('change', function() {
        if ($(this).is(':checked')) {
            $('#buscar-cliente').prop('disabled', true);
            $('#cliente-info').hide();
            $('#cliente_id').val('');
            clienteSeleccionado = null;
        } else {
            $('#buscar-cliente').prop('disabled', false);
        }
    });

    // Mostrar número de operación para pagos digitales
    $('input[name="metodo"]').on('change', function() {
        if (['tarjeta', 'yape', 'plin'].includes($(this).val())) {
            $('#operacion-container').show();
            $('#numero_operacion').prop('required', true);
        } else {
            $('#operacion-container').hide();
            $('#numero_operacion').prop('required', false).val('');
        }
    });

    // Confirmar pago
    $('#btn-confirmar-pago').on('click', function() {
        const clienteId = $('#cliente_id').val();
        const sinCliente = $('#sin-cliente').is(':checked');
        const metodo = $('input[name="metodo"]:checked').val();
        const numeroOperacion = $('#numero_operacion').val();

        // Validaciones
        if (!sinCliente && !clienteId) {
            alert('Por favor selecciona un cliente o marca "Venta sin cliente registrado"');
            return;
        }

        if (['tarjeta', 'yape', 'plin'].includes(metodo) && !numeroOperacion) {
            alert('Por favor ingresa el número de operación');
            return;
        }

        // Procesar pago
        procesarPago(clienteId, metodo, numeroOperacion);
    });

    // Procesar pago - Enviar al servidor
    function procesarPago(clienteId, metodo, numeroOperacion) {
        const btn = $('#btn-confirmar-pago');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');

        $.ajax({
            url: "/ordenes/mesa/" + mesaIdPago + "/procesar-pago",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                cliente_id: clienteId || null,
                metodo: metodo,
                numero_operacion: numeroOperacion,
                monto: montoPago
            },
            success: function(response) {
                console.log('✅ Pago procesado:', response);
                mostrarAlerta('success', response.message);
                $('#modalPago').modal('hide');
                
                // Redirigir después de 2 segundos
                setTimeout(() => {
                    window.location.href = "{{ route('ordenes.index') }}";
                }, 2000);
            },
            error: function(xhr) {
                console.error('❌ Error:', xhr.responseJSON);
                let mensaje = 'Error al procesar el pago';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    mensaje = xhr.responseJSON.message;
                }
                mostrarAlerta('danger', mensaje);
                btn.prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i>Procesar Pago');
            }
        });
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
        }, 4000);
    }

    // Cerrar lista de clientes cuando se da click afuera
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#buscar-cliente, #lista-clientes').length) {
            $('#lista-clientes').hide();
        }
    });
</script>
@endpush
