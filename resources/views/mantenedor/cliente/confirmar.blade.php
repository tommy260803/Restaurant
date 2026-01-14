@extends('layouts.plantilla')

@section('titulo', 'Confirmar Eliminación - Registro de Nacimiento')

@section('contenido')
<div class="container mt-4 px-3 animate__animated animate__fadeIn">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-lg border-0 rounded-3 overflow-hidden">
                {{-- Encabezado con gradiente moderno --}}
                <div class="card-header text-white py-4 border-0" 
                     style="background-image: linear-gradient(to right, #dc2626, #ef4444, #f87171); position: relative;">
                    <div class="position-absolute top-0 start-0 w-100 h-100" 
                         style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);"></div>
                    <div class="position-relative">
                        <h2 class="h3 fw-bold mb-0 d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-3" style="font-size: 1.5rem;"></i>
                            <span class="bg-gradient-text" style="-webkit-background-clip: text; -webkit-text-fill-color: transparent; background-image: linear-gradient(to right, #ffffff, #fef2f2);">
                                Confirmar Eliminación
                            </span>
                        </h2>
                    </div>
                </div>

                <div class="card-body p-5" style="background: linear-gradient(135deg, #fafafa 0%, #f8fafc 100%);">
                    {{-- Mensaje de confirmación con diseño moderno --}}
                    <div class="text-center mb-5">
                        <div class="mb-4">
                            <i class="fas fa-trash-alt text-danger" style="font-size: 4rem; opacity: 0.9;"></i>
                        </div>
                        <h3 class="h4 fw-bold mb-3" style="color: #dc2626;">
                            ¿Está seguro que desea eliminar este registro de nacimiento?
                        </h3>
                        <p class="text-muted mb-0 fs-6">
                            Esta acción no se puede deshacer. El registro será marcado como eliminado permanentemente.
                        </p>
                    </div>

                    {{-- Información del registro con estilo moderno --}}
                    <div class="card border-0 mb-5 shadow-sm rounded-3" 
                         style="background: linear-gradient(135deg, #ffffff, #f1f5f9);">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-4 d-flex align-items-center" style="color: #1e293b;">
                                <i class="fas fa-info-circle text-primary me-3"></i>
                                Detalles del Registro
                            </h6>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-item p-3 rounded-3 shadow-sm" 
                                         style="background: linear-gradient(135deg, #e0f2fe, #f0f9ff); border-left: 4px solid #0ea5e9;">
                                        <label class="fw-semibold text-muted small mb-1">ID ACTA</label>
                                        <p class="mb-0 fw-bold fs-5" style="color: #0369a1;">
                                            #{{ $nacimiento->id_acta_nacimiento }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item p-3 rounded-3 shadow-sm" 
                                         style="background: linear-gradient(135deg, #f3e8ff, #faf5ff); border-left: 4px solid #a855f7;">
                                        <label class="fw-semibold text-muted small mb-1">RECIÉN NACIDO</label>
                                        <p class="mb-0 fw-bold fs-6" style="color: #7c3aed;">
                                            {{ $nacimiento->recienNacido->nombres ?? 'N/A' }} 
                                            {{ $nacimiento->recienNacido->apellido_paterno ?? '' }} 
                                            {{ $nacimiento->recienNacido->apellido_materno ?? '' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item p-3 rounded-3 shadow-sm" 
                                         style="background: linear-gradient(135deg, #ecfdf5, #f0fdf4); border-left: 4px solid #10b981;">
                                        <label class="fw-semibold text-muted small mb-1">FECHA DE REGISTRO</label>
                                        <p class="mb-0 fw-bold fs-6" style="color: #059669;">
                                            {{ $nacimiento->fecha_registro ? \Carbon\Carbon::parse($nacimiento->fecha_registro)->format('d/m/Y') : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item p-3 rounded-3 shadow-sm" 
                                         style="background: linear-gradient(135deg, #fef3c7, #fef9c3); border-left: 4px solid #f59e0b;">
                                        <label class="fw-semibold text-muted small mb-1">ESTADO ACTUAL</label>
                                        <p class="mb-0">
                                            <span class="badge rounded-pill shadow-sm px-3 py-2 fw-semibold {{ $nacimiento->estado == 'Activo' ? 'text-white' : 'text-white' }}" 
                                                  style="background-image: linear-gradient(to right, {{ $nacimiento->estado == 'Activo' ? '#10b981, #059669' : '#f59e0b, #d97706' }});">
                                                {{ $nacimiento->estado ?? 'N/A' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if($nacimiento->folio)
                            <div class="row g-4 mt-2">
                                <div class="col-md-6">
                                    <div class="info-item p-3 rounded-3 shadow-sm" 
                                         style="background: linear-gradient(135deg, #fce7f3, #fdf2f8); border-left: 4px solid #ec4899;">
                                        <label class="fw-semibold text-muted small mb-1">LIBRO</label>
                                        <p class="mb-0 fw-bold fs-6" style="color: #be185d;">
                                            {{ $nacimiento->folio->libro->numero_libro ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item p-3 rounded-3 shadow-sm" 
                                         style="background: linear-gradient(135deg, #e0e7ff, #eef2ff); border-left: 4px solid #6366f1;">
                                        <label class="fw-semibold text-muted small mb-1">FOLIO</label>
                                        <p class="mb-0 fw-bold fs-6" style="color: #4f46e5;">
                                            {{ $nacimiento->folio->numero_folio ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Formulario de eliminación con botones modernos --}}
                    <form method="POST" action="{{ route('nacimiento.destroy', $nacimiento->id_acta_nacimiento) }}">
                        @method('DELETE')
                        @csrf
                        
                        <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                            {{-- Botón Cancelar --}}
                            <a href="{{ route('nacimiento.index') }}" 
                               class="btn btn-lg d-flex align-items-center justify-content-center gap-2 px-5 py-3 shadow-sm fw-semibold rounded-3 border-0"
                               style="background: linear-gradient(135deg, #6b7280, #4b5563); color: white; min-width: 160px; transition: all 0.3s;">
                                <i class="fas fa-times-circle"></i> 
                                No, Cancelar
                            </a>
                            
                            {{-- Botón Confirmar Eliminación --}}
                            <button type="submit" 
                                    class="btn btn-lg d-flex align-items-center justify-content-center gap-2 px-5 py-3 shadow-sm fw-semibold rounded-3 border-0"
                                    style="background: linear-gradient(135deg, #dc2626, #ef4444); color: white; min-width: 160px; transition: all 0.3s;">
                                <i class="fas fa-check-square"></i> 
                                Sí, Eliminar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Efectos hover para los botones
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.02)';
            this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
        });
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
        });
    });

    // Efecto hover para las tarjetas de información
    document.querySelectorAll('.info-item').forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 8px 20px rgba(0,0,0,0.1)';
        });
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 2px 10px rgba(0,0,0,0.05)';
        });
    });
</script>

<style>
    .card {
        transition: all 0.3s ease;
    }
    
    .info-item {
        transition: all 0.3s ease;
        border: 1px solid rgba(255,255,255,0.8);
    }
    
    .btn {
        transition: all 0.3s ease !important;
    }
    
    .btn:hover {
        transform: translateY(-3px) scale(1.02) !important;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    
    .badge {
        font-size: 0.9em;
        letter-spacing: 0.5px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .card-body {
            padding: 2rem 1.5rem !important;
        }
        
        .row.g-4 {
            --bs-gutter-x: 1rem;
            --bs-gutter-y: 1rem;
        }
        
        .btn {
            min-width: 140px !important;
            padding: 0.75rem 1.5rem !important;
        }
    }
    
    /* Animaciones adicionales */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate__animated.animate__fadeIn {
        animation: fadeIn 0.6s ease-out;
    }
</style>
@endsection