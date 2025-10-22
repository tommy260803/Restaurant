{{-- filepath: resources/views/mantenedor/proveedor/confirmar.blade.php --}}
@extends('layouts.plantilla')

@section('contenido')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light px-3">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                <div class="card shadow-lg border-0" style="border-radius: 1rem;">
                    <div class="card-body p-4 p-md-5 text-center">
                        <div class="d-flex flex-column align-items-center">
                            <!-- Icono animado -->
                            <div class="text-danger mb-4" style="font-size: 3rem;">
                                <i class="fas fa-exclamation-triangle bounce-animation"></i>
                            </div>
                            
                            <!-- Título -->
                            <h1 class="h3 fw-bold text-dark mb-3">¿Desea desactivar este proveedor?</h1>
                            
                            <!-- Información del registro -->
                            <p class="text-muted small mb-4">
                                <span class="fw-medium">Proveedor:</span> 
                                <span class="text-primary">{{ $proveedor->nombre }} {{ $proveedor->apellidoPaterno ?? '' }} {{ $proveedor->apellidoMaterno ?? '' }}</span><br>
                                <span class="fw-medium">ID:</span>
                                <span class="text-primary">{{ $proveedor->idProveedor }}</span>
                            </p>

                            <!-- Formulario -->
                            <form method="POST" action="{{ route('proveedor.destroy', $proveedor->idProveedor) }}" class="w-100">
                                @method('DELETE')
                                @csrf

                                <div class="d-flex flex-column flex-md-row gap-3 justify-content-center mt-4">
                                    <!-- Botón Confirmar -->
                                    <button type="submit" 
                                            class="btn btn-danger d-flex align-items-center justify-content-center gap-2 px-4 py-2 fw-semibold shadow-sm hover-scale">
                                        <i class="fas fa-check-circle"></i> 
                                        Confirmar Desactivación
                                    </button>

                                    <!-- Botón Cancelar -->
                                    <a href="{{ route('proveedor.index') }}" 
                                       class="btn btn-secondary d-flex align-items-center justify-content-center gap-2 px-4 py-2 fw-semibold shadow-sm hover-scale text-decoration-none">
                                        <i class="fas fa-times-circle"></i> 
                                        Cancelar
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

{{-- CSS para animaciones y efectos --}}
<style>
    .card {
        border-radius: 1rem !important;
        border: 1px solid rgba(0,0,0,.125);
    }
    .bounce-animation {
        animation: bounce 2s infinite;
    }
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }
    .hover-scale { transition: all 0.2s ease-in-out; }
    .hover-scale:hover { transform: scale(1.05); }
    .btn:focus { box-shadow: 0 0 0 0.2rem rgba(var(--bs-btn-focus-shadow-rgb), .5); }
    .btn-danger:focus { box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, .25); }
    .btn-secondary:focus { box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, .25); }
    @media (max-width: 768px) {
        .card-body { padding: 2rem 1.5rem !important; }
        .btn { width: 100%; }
    }
    .shadow-lg { box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important; }
</style>
@endsection