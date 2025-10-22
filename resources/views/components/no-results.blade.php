@props(['mensaje' => 'No se encontraron resultados'])

<div class="no-results text-center text-muted py-5">
    <i class="fas fa-search fa-3x mb-3 opacity-50"></i>
    <h4>{{ $mensaje }}</h4>
    <p class="text-muted">Intenta con otros términos de búsqueda o verifica los filtros aplicados.</p>
</div>
