{{-- ordenes/partials/modal-nota.blade.php--}}
{{-- Modal: Agregar/Editar Nota --}}
<div class="modal fade" id="modalNota" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #2d2d44; border: 1px solid #3a3a54;">
            
            {{-- Header --}}
            <div class="modal-header" style="border-bottom: 1px solid #3a3a54;">
                <h5 class="modal-title text-white">Agregar Nota</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                <form id="form-nota">
                    <input type="hidden" id="nota-plato-id">
                    
                    <div class="mb-3">
                        <label class="form-label text-white">Nota o instrucciones especiales:</label>
                        <textarea class="form-control" 
                                  id="nota-texto" 
                                  rows="4" 
                                  maxlength="500"
                                  placeholder="Ej: Sin cebolla, poco picante, extra queso..."
                                  style="background-color: #3a3a54; border-color: #3a3a54; color: #fff;"></textarea>
                        <div class="form-text" style="color: #b8b8d1;">
                            <span id="nota-contador">0</span> / 500 caracteres
                        </div>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <div class="modal-footer" style="border-top: 1px solid #3a3a54;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="btn-guardar-nota">
                    <i class="bi bi-check-circle me-1"></i>Guardar Nota
                </button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    // Contador de caracteres
    $('#nota-texto').on('input', function() {
        const length = $(this).val().length;
        $('#nota-contador').text(length);
    });

    // Abrir modal de nota (delegación de eventos)
    $(document).on('click', '.btn-nota', function() {
        const fila = $(this).closest('tr');
        const platoId = fila.data('plato-id');
        
        // Obtener nota completa desde el atributo data-nota-completa
        const notaElement = fila.find('.text-info');
        const notaActual = notaElement.data('nota-completa') || notaElement.text() || '';
        
        $('#nota-plato-id').val(platoId);
        $('#nota-texto').val(notaActual);
        $('#nota-contador').text(notaActual.length);
        
        $('#modalNota').modal('show');
    });

    // Guardar nota
    $('#btn-guardar-nota').on('click', function() {
        const btn = $(this);
        const platoId = $('#nota-plato-id').val();
        const nota = $('#nota-texto').val();
        
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Guardando...');

        $.ajax({
            url: urlActualizarNota,
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                plato_id: platoId,
                nota: nota
            },
            success: function(response) {
                mostrarAlerta('success', response.message);
                
                // Actualizar nota en la tabla
                const fila = $(`tr[data-plato-id="${platoId}"]`);
                const btnNota = fila.find('.btn-nota');
                
                // Remover nota anterior si existe
                btnNota.next('.text-info').remove();
                
                // Agregar nueva nota si no está vacía
                if (nota.trim() !== '') {
                    // Mostrar preview corto pero guardar nota completa en data-nota
                    const notaPreview = nota.length > 30 ? nota.substring(0, 30) + '...' : nota;
                    btnNota.after(`<small class="text-info d-block mt-1" data-nota-completa="${nota.replace(/"/g, '&quot;')}" style="cursor: help;" title="${nota.replace(/"/g, '&quot;')}">${notaPreview}</small>`);
                }
                
                // Cerrar modal
                $('#modalNota').modal('hide');
                
                // Resetear botón
                btn.prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i>Guardar Nota');
            },
            error: function(xhr) {
                mostrarAlerta('danger', 'Error al guardar la nota');
                btn.prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i>Guardar Nota');
            }
        });
    });
</script>
@endpush