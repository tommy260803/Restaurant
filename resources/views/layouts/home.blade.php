@extends('layouts.plantilla')

@section('contenido')
<div class="container-fluid py-4">
    
    {{-- Header --}}
    <div class="text-center mb-5">
        <div class="header-welcome">
            <h1 class="h2 fw-bold text-primary mb-2">
                <i class="bi bi-grid-3x3-gap me-2"></i>
                Sistema de Gestión de Restaurante
            </h1>
            <p class="text-muted lead">Bienvenido, {{ auth()->user()->nombre_usuario ?? 'Usuario' }}</p>
        </div>
    </div>

    {{-- Grid de Módulos --}}
    <div class="row g-4">
        
        {{-- ================= DELIVERY ================= --}}
        
        {{-- DELIVERY - COCINA --}}
        @if(auth()->user()->hasRole('cocinero') || auth()->user()->hasRole('administrador'))
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card module-card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="module-icon mb-3">
                        <div class="icon-circle bg-success bg-opacity-10">
                            <i class="bi bi-bicycle text-success"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold mb-2">Delivery - Cocina</h5>
                    <p class="card-text text-muted small mb-4">
                        Pedidos delivery para preparación
                    </p>
                    <a href="{{ route('cocina.delivery.index') }}" class="btn btn-success w-100">
                        <i class="bi bi-egg-fried me-2"></i>Ver Pedidos
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- DELIVERY - GESTIÓN --}}
        @if(auth()->user()->hasRole('cajero') || auth()->user()->hasRole('administrador'))
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card module-card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="module-icon mb-3">
                        <div class="icon-circle bg-warning bg-opacity-10">
                            <i class="bi bi-bicycle text-warning"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold mb-2">Delivery - Gestión</h5>
                    <p class="card-text text-muted small mb-4">
                        Gestión y seguimiento de pedidos
                    </p>
                    <a href="{{ route('admin.delivery.index') }}" class="btn btn-warning w-100">
                        <i class="bi bi-cash-register me-2"></i>Gestionar
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- MESAS --}}
        @if(auth()->user()->hasRole('administrador'))
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card module-card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="module-icon mb-3">
                        <div class="icon-circle bg-primary bg-opacity-10">
                            <i class="bi bi-table text-primary"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold mb-2">Mesas</h5>
                    <p class="card-text text-muted small mb-4">
                        Gestión de mesas y disponibilidad
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('mesas.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-list me-2"></i>Ver Lista
                        </a>
                        <a href="{{ route('mesas.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-2"></i>Registrar
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- CATEGORÍAS --}}
        @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('cocinero'))
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card module-card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="module-icon mb-3">
                        <div class="icon-circle bg-warning bg-opacity-10">
                            <i class="bi bi-folder text-warning"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold mb-2">Categorías</h5>
                    <p class="card-text text-muted small mb-4">
                        Clasificación de platos del menú
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('mantenedor.categorias.index') }}" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-list me-2"></i>Ver Lista
                        </a>
                        <a href="{{ route('mantenedor.categorias.create') }}" class="btn btn-warning btn-sm text-white">
                            <i class="bi bi-plus-circle me-2"></i>Registrar
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- PLATOS --}}
        @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('cocinero'))
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card module-card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="module-icon mb-3">
                        <div class="icon-circle bg-success bg-opacity-10">
                            <i class="bi bi-egg-fried text-success"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold mb-2">Platos</h5>
                    <p class="card-text text-muted small mb-4">
                        Administración del menú y precios
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('mantenedor.platos.index') }}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-list me-2"></i>Ver Lista
                        </a>
                        <a href="{{ route('mantenedor.platos.create') }}" class="btn btn-success btn-sm">
                            <i class="bi bi-plus-circle me-2"></i>Registrar
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ÓRDENES --}}
        @if(auth()->user()->hasRole('mesero') || auth()->user()->hasRole('cajero') || auth()->user()->hasRole('administrador'))
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card module-card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="module-icon mb-3">
                        <div class="icon-circle bg-danger bg-opacity-10">
                            <i class="bi bi-receipt text-danger"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold mb-2">Órdenes</h5>
                    <p class="card-text text-muted small mb-4">
                        Gestión de pedidos por mesa
                    </p>
                    <a href="{{ route('ordenes.index') }}" class="btn btn-danger w-100">
                        <i class="bi bi-clipboard-check me-2"></i>Ver Órdenes
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- RESERVAS --}}
        @if(auth()->user()->hasRole('cajero') || auth()->user()->hasRole('administrador'))
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card module-card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="module-icon mb-3">
                        <div class="icon-circle bg-info bg-opacity-10">
                            <i class="bi bi-calendar-check text-info"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold mb-2">Reservas</h5>
                    <p class="card-text text-muted small mb-4">
                        Gestión de reservas de clientes
                    </p>
                    <a href="{{ route('reservas.index') }}" class="btn btn-info w-100 text-white">
                        <i class="bi bi-list-check me-2"></i>Ver Reservas
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- COCINA --}}
        @if(auth()->user()->hasRole('cocinero') || auth()->user()->hasRole('administrador'))
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card module-card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="module-icon mb-3">
                        <div class="icon-circle bg-secondary bg-opacity-10">
                            <i class="bi bi-fire text-secondary"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold mb-2">Cocina</h5>
                    <p class="card-text text-muted small mb-4">
                        Panel de pedidos en cocina
                    </p>
                    <a href="{{ route('cocinero.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-egg-fried me-2"></i>Panel Cocina
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- PROVEEDORES --}}
        @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('almacenero'))
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card module-card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="module-icon mb-3">
                        <div class="icon-circle bg-dark bg-opacity-10">
                            <i class="bi bi-truck text-dark"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold mb-2">Proveedores</h5>
                    <p class="card-text text-muted small mb-4">
                        Gestión de proveedores y compras
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('proveedor.index') }}" class="btn btn-outline-dark btn-sm">
                            <i class="bi bi-list me-2"></i>Ver Lista
                        </a>
                        <a href="{{ route('proveedor.create') }}" class="btn btn-dark btn-sm">
                            <i class="bi bi-plus-circle me-2"></i>Registrar
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- COMPRAS --}}
        @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('almacenero'))
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card module-card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="module-icon mb-3">
                        <div class="icon-circle bg-primary bg-opacity-10">
                            <i class="bi bi-cart text-primary"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold mb-2">Compras</h5>
                    <p class="card-text text-muted small mb-4">
                        Registro de órdenes de compra
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('compras.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-list me-2"></i>Ver Lista
                        </a>
                        <a href="{{ route('compras.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-2"></i>Nueva Compra
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- INGREDIENTES --}}
        @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('almacenero'))
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card module-card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="module-icon mb-3">
                        <div class="icon-circle bg-success bg-opacity-10">
                            <i class="bi bi-box-seam text-success"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold mb-2">Ingredientes</h5>
                    <p class="card-text text-muted small mb-4">
                        Control de inventario y stock
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('ingredientes.index') }}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-list me-2"></i>Ver Lista
                        </a>
                        <a href="{{ route('ingredientes.create') }}" class="btn btn-success btn-sm">
                            <i class="bi bi-plus-circle me-2"></i>Registrar
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- REPORTES --}}
        @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('cajero'))
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card module-card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="module-icon mb-3">
                        <div class="icon-circle bg-warning bg-opacity-10">
                            <i class="bi bi-graph-up text-warning"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold mb-2">Reportes</h5>
                    <p class="card-text text-muted small mb-4">
                        Estadísticas y reportes del sistema
                    </p>
                    <a href="{{ route('reportes.index') }}" class="btn btn-warning w-100">
                        <i class="bi bi-bar-chart me-2"></i>Ver Reportes
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- USUARIOS --}}
        @if(auth()->user()->hasRole('administrador'))
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card module-card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="module-icon mb-3">
                        <div class="icon-circle bg-info bg-opacity-10">
                            <i class="bi bi-people text-info"></i>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold mb-2">Usuarios</h5>
                    <p class="card-text text-muted small mb-4">
                        Gestión de usuarios del sistema
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-list me-2"></i>Ver Lista
                        </a>
                        <a href="{{ route('usuarios.create') }}" class="btn btn-info btn-sm text-white">
                            <i class="bi bi-person-plus me-2"></i>Registrar
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('styles')
<style>
    /* Asegurar que Bootstrap Icons se cargue */
    @import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css');
    
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
    }
    
    /* Header mejorado */
    .header-welcome {
        padding: 2rem 0;
        animation: fadeInDown 0.5s ease;
    }
    
    .header-welcome h1 {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Cards de módulos */
    .module-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        cursor: pointer;
        animation: fadeInUp 0.5s ease;
    }
    
    .module-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }
    
    /* Iconos de módulos */
    .module-icon {
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .icon-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .module-card:hover .icon-circle {
        transform: scale(1.1) rotate(5deg);
    }
    
    .icon-circle i {
        font-size: 2rem;
    }
    
    /* Títulos de cards */
    .card-title {
        font-size: 1.1rem;
        color: #2c3e50;
    }
    
    .card-text {
        font-size: 0.875rem;
        line-height: 1.5;
        min-height: 40px;
    }
    
    /* Botones mejorados */
    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        font-size: 0.875rem;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.8rem;
    }
    
    /* Animaciones */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Escalonado de animaciones */
    .col-lg-3:nth-child(1) .module-card { animation-delay: 0.05s; }
    .col-lg-3:nth-child(2) .module-card { animation-delay: 0.1s; }
    .col-lg-3:nth-child(3) .module-card { animation-delay: 0.15s; }
    .col-lg-3:nth-child(4) .module-card { animation-delay: 0.2s; }
    .col-lg-3:nth-child(5) .module-card { animation-delay: 0.25s; }
    .col-lg-3:nth-child(6) .module-card { animation-delay: 0.3s; }
    .col-lg-3:nth-child(7) .module-card { animation-delay: 0.35s; }
    .col-lg-3:nth-child(8) .module-card { animation-delay: 0.4s; }
    
    /* Responsive */
    @media (max-width: 768px) {
        .header-welcome h1 {
            font-size: 1.75rem;
        }
        
        .icon-circle {
            width: 60px;
            height: 60px;
        }
        
        .icon-circle i {
            font-size: 1.75rem;
        }
        
        .module-icon {
            height: 70px;
        }
    }
    
    /* Estados hover para iconos específicos */
    .module-card:hover .bg-success { background-color: rgba(25, 135, 84, 0.2) !important; }
    .module-card:hover .bg-warning { background-color: rgba(255, 193, 7, 0.2) !important; }
    .module-card:hover .bg-primary { background-color: rgba(13, 110, 253, 0.2) !important; }
    .module-card:hover .bg-danger { background-color: rgba(220, 53, 69, 0.2) !important; }
    .module-card:hover .bg-info { background-color: rgba(13, 202, 240, 0.2) !important; }
    .module-card:hover .bg-secondary { background-color: rgba(108, 117, 125, 0.2) !important; }
    .module-card:hover .bg-dark { background-color: rgba(33, 37, 41, 0.2) !important; }
</style>
@endpush