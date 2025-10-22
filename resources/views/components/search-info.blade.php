@props([
    'termino' => '',
    'campo' => '',
    'total' => 0,
])

<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    Mostrando resultados para:
    <strong>"{{ $termino }}"</strong> en <strong>{{ $campo }}</strong>
    <span class="badge badge-info ms-2">{{ $total }} resultado(s)</span>
</div>
