/**
 * ordenes.js
 * Lógica de interacciones para el módulo de órdenes
 * Coloca este archivo en: public/js/ordenes.js
 */

$(document).ready(function() {
    
    // ============================================
    // CONFIGURAR CSRF TOKEN PARA AJAX
    // ============================================
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // ============================================
    // MANEJO DE CANTIDAD (+ / -)
    // ============================================

    // Botón para incrementar cantidad
    $(document).on('click', '.btn-cantidad-mas', function() {
        const input = $(this).siblings('.input-cantidad');
        let cantidad = parseInt(input.val()) || 1;
        
        if (cantidad < 50) {
            cantidad++;
            input.val(cantidad);
            actualizarCantidadPlato(input);
        }
    });

    // Botón para decrementar cantidad
    $(document).on('click', '.btn-cantidad-menos', function() {
        const input = $(this).siblings('.input-cantidad');
        let cantidad = parseInt(input.val()) || 1;
        
        if (cantidad > 1) {
            cantidad--;
            input.val(cantidad);
            actualizarCantidadPlato(input);
        }
    });

    // Cambio manual del input
    $(document).on('change', '.input-cantidad', function() {
        let cantidad = parseInt($(this).val()) || 1;
        
        // Validar rango
        if (cantidad < 1) cantidad = 1;
        if (cantidad > 50) cantidad = 50;
        
        $(this).val(cantidad);
        actualizarCantidadPlato($(this));
    });

    /**
     * Función para actualizar cantidad de un plato vía AJAX
     */
    function actualizarCantidadPlato(input) {
        const fila = input.closest('tr');
        const platoId = fila.data('plato-id');
        const cantidad = parseInt(input.val());
        const precioText = fila.find('td:eq(1)').text().replace(/[^0-9.]/g, '');
        const precio = parseFloat(precioText);

        // Actualizar subtotal localmente (optimista)
        const subtotal = precio * cantidad;
        fila.find('.subtotal').text('$' + subtotal.toFixed(2));

        // Enviar a servidor
        $.ajax({
            url: urlActualizarCantidad,
            method: 'POST',
            data: {
                plato_id: platoId,
                cantidad: cantidad
            },
            success: function(response) {
                // Actualizar total general
                $('#total-orden').text('$' + parseFloat(response.total).toFixed(2));
                
                // Actualizar subtotal con valor exacto del servidor
                fila.find('.subtotal').text('$' + parseFloat(response.subtotal).toFixed(2));
            },
            error: function(xhr) {
                console.error('Error al actualizar cantidad:', xhr);
                mostrarAlerta('danger', 'Error al actualizar cantidad');
                
                // Revertir cambio - recargar página
                location.reload();
            }
        });
    }

    // ============================================
    // ELIMINAR PLATO
    // ============================================

    $(document).on('click', '.btn-eliminar', function() {
        if (!confirm('¿Eliminar este plato de la orden?')) {
            return;
        }

        const fila = $(this).closest('tr');
        const platoId = fila.data('plato-id');
        const url = urlEliminarPlato.replace(':plato', platoId);

        $.ajax({
            url: url,
            method: 'DELETE',
            success: function(response) {
                mostrarAlerta('success', response.message);
                
                // Remover fila
                fila.fadeOut(300, function() {
                    $(this).remove();
                    
                    // Verificar si quedan platos
                    const cantidadPlatos = $('#tbody-platos tr').length;
                    
                    if (cantidadPlatos === 0) {
                        // Mostrar mensaje de tabla vacía
                        $('#tbody-platos').html(`
                            <tr id="fila-vacia">
                                <td colspan="6" class="text-center py-4" style="color: #b8b8d1;">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    No hay platos en esta orden. Agrega platos usando el botón de abajo.
                                </td>
                            </tr>
                        `);
                        
                        // Deshabilitar botón cobrar
                        $('#btn-cobrar').prop('disabled', true);
                    }
                    
                    // Actualizar total
                    $('#total-orden').text('$' + parseFloat(response.total).toFixed(2));
                    
                    // Actualizar cantidad de items
                    $('#cantidad-items').text(cantidadPlatos);
                });
            },
            error: function(xhr) {
                console.error('Error al eliminar:', xhr);
                mostrarAlerta('danger', 'Error al eliminar plato');
            }
        });
    });

    // ============================================
    // CONFIRMACIÓN DE COBRO
    // ============================================

    $('#form-cobrar').on('submit', function(e) {
        const total = $('#total-orden').text();
        
        if (!confirm(`¿Procesar cobro de ${total}?\n\nEsta acción cerrará la mesa y generará el registro de venta.`)) {
            e.preventDefault();
            return false;
        }
    });

    // ============================================
    // TOOLTIPS (Bootstrap)
    // ============================================

    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

});

/**
 * Función auxiliar para mostrar alertas flotantes
 */
function mostrarAlerta(tipo, mensaje) {
    const iconos = {
        'success': 'check-circle',
        'danger': 'exclamation-triangle',
        'warning': 'exclamation-circle',
        'info': 'info-circle'
    };

    const alerta = `
        <div class="alert alert-${tipo} alert-dismissible fade show position-fixed top-0 end-0 m-3" 
             role="alert" 
             style="z-index: 9999; min-width: 300px;">
            <i class="bi bi-${iconos[tipo]} me-2"></i>
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('body').append(alerta);
    
    // Auto-cerrar después de 4 segundos
    setTimeout(() => {
        $('.alert').fadeOut(400, function() {
            $(this).remove();
        });
    }, 4000);
}

/**
 * Función para formatear números como moneda
 */
function formatearMoneda(numero) {
    return '$' + parseFloat(numero).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}