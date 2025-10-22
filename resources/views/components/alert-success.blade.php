<div {{ $attributes->merge(['class' => 'alert alert-success alert-dismissible fade show']) }} role="alert">
    <i class="fas fa-check-circle me-2"></i>
    {{ $slot }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
</div>
