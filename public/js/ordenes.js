// public/js/ordenes.js
(function() {
    'use strict';

    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || 
                       document.querySelector('input[name="_token"]')?.value;

    // ============================================
    // MANEJO DE CANTIDADES
    // ============================================

    // Botón menos (-)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-cantidad-menos')) {
            const btn = e.target.closest('.btn-cantidad-menos');
            const row = btn.closest('tr');
            const input = row.querySelector('.input-cantidad');
            const platoId = row.dataset.platoId;
            
            let cantidad = parseInt(input.value) || 1;
            if (cantidad > 1) {
                cantidad--;
                input.value = cantidad;
                actualizarCantidad(platoId, cantidad);
            }
        }
    });

    // Botón más (+)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-cantidad-mas')) {
            const btn = e.target.closest('.btn-cantidad-mas');
            const row = btn.closest('tr');
            const input = row.querySelector('.input-cantidad');
            const platoId = row.dataset.platoId;
            
            let cantidad = parseInt(input.value) || 1;
            if (cantidad < 50) {
                cantidad++;
                input.value = cantidad;
                actualizarCantidad(platoId, cantidad);
            }
        }
    });

    // Input directo
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('input-cantidad')) {
            const input = e.target;
            const row = input.closest('tr');
            const platoId = row.dataset.platoId;
            
            let cantidad = parseInt(input.value) || 1;
            cantidad = Math.max(1, Math.min(50, cantidad));
            input.value = cantidad;
            
            actualizarCantidad(platoId, cantidad);
        }
    });

    async function actualizarCantidad(platoId, cantidad) {
        try {
            const response = await fetch(urlActualizarCantidad, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    plato_id: platoId,
                    cantidad: cantidad
                })
            });

            const data = await response.json();

            if (data.success) {
                // Actualizar subtotal en la fila
                const row = document.querySelector(`tr[data-plato-id="${platoId}"]`);
                if (row) {
                    const subtotalCell = row.querySelector('.subtotal');
                    if (subtotalCell) {
                        subtotalCell.textContent = '$' + parseFloat(data.subtotal).toFixed(2);
                    }
                }

                // Actualizar total general
                actualizarTotal(data.total);
            } else {
                alert(data.message || 'Error al actualizar cantidad');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al actualizar cantidad');
        }
    }

    // ============================================
    // ELIMINAR PLATO
    // ============================================

    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-eliminar')) {
            const btn = e.target.closest('.btn-eliminar');
            const row = btn.closest('tr');
            const platoId = row.dataset.platoId;

            if (confirm('¿Eliminar este plato de la orden?')) {
                eliminarPlato(platoId, row);
            }
        }
    });

    async function eliminarPlato(platoId, row) {
        try {
            const url = urlEliminarPlato.replace(':plato', platoId);
            
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                // Remover fila con animación
                row.style.opacity = '0';
                row.style.transition = 'opacity 0.3s';
                
                setTimeout(() => {
                    row.remove();
                    
                    // Verificar si hay platos
                    const tbody = document.getElementById('tbody-platos');
                    if (tbody && tbody.querySelectorAll('tr:not(#fila-vacia)').length === 0) {
                        tbody.innerHTML = `
                            <tr id="fila-vacia">
                                <td colspan="6" class="text-center py-4" style="color: #b8b8d1;">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    No hay platos en esta orden.
                                </td>
                            </tr>`;
                        
                        // Deshabilitar botón cobrar
                        const btnCobrar = document.getElementById('btn-cobrar');
                        if (btnCobrar) btnCobrar.disabled = true;
                    }
                    
                    // Actualizar total
                    actualizarTotal(data.total);
                    
                    // Actualizar cantidad de items
                    actualizarCantidadItems();
                }, 300);
            } else {
                alert(data.message || 'Error al eliminar plato');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al eliminar plato');
        }
    }

    // ============================================
    // NOTAS
    // ============================================

    let platoIdActual = null;

    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-nota')) {
            const btn = e.target.closest('.btn-nota');
            const row = btn.closest('tr');
            platoIdActual = row.dataset.platoId;
            
            // Obtener nota actual
            const notaElement = row.querySelector('[data-nota-completa]');
            const notaActual = notaElement ? notaElement.dataset.notaCompleta : '';
            
            // Llenar modal
            const textarea = document.getElementById('nota-textarea');
            if (textarea) {
                textarea.value = notaActual;
                actualizarContadorCaracteres();
            }
            
            // Abrir modal
            const modal = new bootstrap.Modal(document.getElementById('modalNota'));
            modal.show();
        }
    });

    // Guardar nota
    const btnGuardarNota = document.getElementById('btn-guardar-nota');
    if (btnGuardarNota) {
        btnGuardarNota.addEventListener('click', async function() {
            const textarea = document.getElementById('nota-textarea');
            const nota = textarea ? textarea.value : '';
            
            if (!platoIdActual) return;
            
            try {
                const response = await fetch(urlActualizarNota, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        plato_id: platoIdActual,
                        nota: nota
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Actualizar nota en la tabla
                    const row = document.querySelector(`tr[data-plato-id="${platoIdActual}"]`);
                    if (row) {
                        const notaCell = row.querySelector('td:nth-child(5)');
                        if (notaCell) {
                            const btnNota = notaCell.querySelector('.btn-nota');
                            let html = btnNota.outerHTML;
                            
                            if (nota) {
                                const notaCorta = nota.length > 30 ? nota.substring(0, 30) + '...' : nota;
                                html += `
                                    <small class="text-info d-block mt-1" 
                                           data-nota-completa="${nota}" 
                                           style="cursor: help;" 
                                           title="${nota}">
                                        ${notaCorta}
                                    </small>`;
                            }
                            
                            notaCell.innerHTML = html;
                        }
                    }
                    
                    // Cerrar modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalNota'));
                    if (modal) modal.hide();
                    
                    platoIdActual = null;
                } else {
                    alert(data.message || 'Error al guardar nota');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al guardar nota');
            }
        });
    }

    // Contador de caracteres
    const notaTextarea = document.getElementById('nota-textarea');
    if (notaTextarea) {
        notaTextarea.addEventListener('input', actualizarContadorCaracteres);
    }

    function actualizarContadorCaracteres() {
        const textarea = document.getElementById('nota-textarea');
        const contador = document.getElementById('contador-caracteres');
        if (textarea && contador) {
            const length = textarea.value.length;
            contador.textContent = `${length}/500`;
        }
    }

    // ============================================
    // FUNCIONES AUXILIARES
    // ============================================

    function actualizarTotal(total) {
        const totalElement = document.getElementById('total-orden');
        if (totalElement) {
            totalElement.textContent = '$' + parseFloat(total).toFixed(2);
            
            // Animar
            totalElement.classList.add('text-primary');
            setTimeout(() => {
                totalElement.classList.remove('text-primary');
                totalElement.classList.add('text-success');
            }, 300);
        }
    }

    function actualizarCantidadItems() {
        const tbody = document.getElementById('tbody-platos');
        if (tbody) {
            const count = tbody.querySelectorAll('tr:not(#fila-vacia)').length;
            const itemsElement = document.getElementById('cantidad-items');
            if (itemsElement) {
                itemsElement.textContent = count;
            }
        }
    }

    // ============================================
    // INICIALIZACIÓN
    // ============================================

    // Inicializar tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

})();