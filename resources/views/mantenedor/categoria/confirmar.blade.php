@extends('layouts.plantilla')

@section('titulo', 'Confirmar Desactivación')

@section('contenido')

{{-- Contenedor principal centrado, eliminamos min-vh-100 para que suba --}}

<div class="d-flex align-items-center justify-content-center bg-body-tertiary px-3 py-5">
<div class="container">
<div class="row justify-content-center">
{{-- Máximo ancho de columna: col-lg-10 col-xl-10 para ocupar casi todo el espacio horizontal --}}
<div class="col-12 col-sm-10 col-md-10 col-lg-10 col-xl-10">
{{-- Tarjeta de confirmación con sombra profunda y borde de peligro --}}
<div class="card shadow-2xl border-danger border-4 custom-card-dark" style="border-radius: 1.5rem; overflow: hidden;">
{{-- Aumentamos el padding para el espacio interior --}}
<div class="card-body p-6 text-center">
<div class="d-flex flex-column align-items-center">

                        <!-- Icono de Advertencia Grande y Animado -->
                        <div class="text-danger mb-4" style="font-size: 4.5rem;">
                            <i class="fas fa-exclamation-triangle bounce-animation"></i>
                        </div>

                        <!-- Título -->
                        <h1 class="h4 fw-bolder text-dark custom-text-dark mb-3">Confirmar Desactivación</h1>
                        <p class="text-secondary custom-text-secondary mb-4">
                            Esta acción es irreversible. ¿Está seguro que desea desactivar la siguiente categoría?
                        </p>

                        <!-- Bloque de Información de la Categoría con padding ajustado y fondo adaptable -->
                        <div class="w-100 p-4 rounded-lg border custom-info-block mb-4 text-start">
                            <p class="mb-1">
                                <span class="fw-medium text-dark custom-text-dark">Nombre:</span>
                                <span class="fw-bold text-primary">{{ $categoria->nombre }}</span>
                            </p>
                            <p class="mb-0">
                                <span class="fw-medium text-dark custom-text-dark">ID:</span>
                                <span class="text-muted custom-text-muted">{{ $categoria->idCategoria }}</span>
                            </p>
                        </div>

                        <!-- Formulario de Desactivación -->
                        <form method="POST"
                            action="{{ route('mantenedor.categorias.destroy', $categoria->idCategoria) }}"
                            class="w-100">
                            @method('DELETE')
                            @csrf
                            <div class="d-grid gap-3 mt-4">
                                <button type="submit"
                                    class="btn btn-danger btn-lg d-flex align-items-center justify-content-center gap-2 px-4 py-2 fw-semibold shadow-lg hover-scale-danger">
                                    <i class="fas fa-check-circle"></i> Sí, Confirmar Desactivación
                                </button>
                                <a href="{{ route('mantenedor.categorias.index') }}"
                                    class="btn btn-outline-secondary btn-lg d-flex align-items-center justify-content-center gap-2 px-4 py-2 fw-semibold hover-scale-secondary text-decoration-none">
                                    <i class="fas fa-times-circle"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

{{-- Estilos visuales mejorados y animación --}}

<style>
/* Sombra más profunda para la tarjeta principal */
.shadow-2xl {
box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.rounded-lg {
border-radius: 0.75rem;
}

/* Clases de Padding más grandes: p-6 /
.p-6 {
padding: 4.5rem !important; / Valor grande para escritorio /
}
@media (max-width: 992px) { / Para tablets y móviles (Bootstrap lg breakpoint) /
.p-6 {
padding: 3.5rem !important;
}
}
@media (max-width: 576px) { / Para móviles pequeños */
.p-6 {
padding: 2.5rem !important;
}
}

/* --- ESTILOS PARA MODO OSCURO --- */

/* Por defecto (Modo Claro) /
.custom-card-dark {
background-color: #ffffff; / Fondo claro /
}
.custom-info-block {
background-color: #f8f9fa; / Fondo gris claro /
border-color: #e9ecef !important;
}
.custom-text-dark {
color: #212529 !important; / Texto oscuro /
}
.custom-text-secondary {
color: #6c757d !important; / Texto secundario gris */
}
.custom-text-muted {
color: #6c757d !important;
}

/* Adaptación para Modo Oscuro (Si su body tiene la clase 'dark-mode' o similar) /
body.dark-mode .custom-card-dark,
.dark-mode .custom-card-dark {
background-color: #212529; / Fondo oscuro, similar a bg-dark o bg-body-secondary /
box-shadow: 0 25px 50px -12px rgba(255, 255, 255, 0.1);
}
body.dark-mode .custom-info-block,
.dark-mode .custom-info-block {
background-color: #343a40; / Fondo más oscuro para el bloque de info /
border-color: #495057 !important;
}
body.dark-mode .custom-text-dark,
.dark-mode .custom-text-dark {
color: #f8f9fa !important; / Texto claro /
}
body.dark-mode .custom-text-secondary,
.dark-mode .custom-text-secondary {
color: #adb5bd !important; / Texto secundario más claro */
}
body.dark-mode .custom-text-muted,
.dark-mode .custom-text-muted {
color: #adb5bd !important;
}

/* --- ANIMACIONES Y HOVERS --- */
.bounce-animation {
animation: bounce 2s infinite;
}

@keyframes bounce {
0%, 20%, 50%, 80%, 100% {
transform: translateY(0);
}
40% {
transform: translateY(-15px);
}
60% {
transform: translateY(-7px);
}
}

.hover-scale-danger {
transition: all 0.2s ease-in-out;
}

.hover-scale-danger:hover {
transform: scale(1.03);
/* Sombra de color rojo al hacer hover */
box-shadow: 0 0 20px rgba(220, 53, 69, 0.7);
}

.hover-scale-secondary {
transition: all 0.2s ease-in-out;
}

.hover-scale-secondary:hover {
transform: scale(1.03);
}
</style>

@endsection