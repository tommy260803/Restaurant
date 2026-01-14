@extends('layouts.plantilla')

@section('styles')
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('contenido')
<div class="container-fluid">
    <!-- Header -->
    <div class="text-center mb-5">
        <div class="d-flex justify-content-center align-items-center mb-3">
            <h2 style="font-weight: bold; font-size: 30px;" class="lead text-muted mb-0">
                Sistema de Gestión de Restaurante
            </h2>
        </div>
    </div>

    <!-- Opciones principales -->
    <div class="row justify-content-center g-4 mb-4">
        {{-- ================= DELIVERY ================= --}}

{{-- DELIVERY - COCINA --}}
@if(auth()->user()->hasRole('cocinero') || auth()->user()->hasRole('administrador'))
<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="card h-100 shadow border-0">
        <div class="card-body text-center p-4">
            <div class="service-icon mb-3">
                <i class="fas fa-motorcycle fa-3x text-success"></i>
            </div>
            <h5 class="card-title fw-bold mb-3">Delivery - Cocina</h5>
            <p class="card-text text-muted mb-4">
                Pedidos delivery para preparación.
            </p>
            <div class="d-grid gap-2">
                <a href="{{ route('cocina.delivery.index') }}" class="btn btn-success">
                    <i class="fas fa-utensils me-2"></i>Ver Pedidos
                </a>
            </div>
        </div>
    </div>
</div>
@endif

{{-- DELIVERY - GESTIÓN --}}
@if(auth()->user()->hasRole('cajero') || auth()->user()->hasRole('administrador'))
<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="card h-100 shadow border-0">
        <div class="card-body text-center p-4">
            <div class="service-icon mb-3">
                <i class="fas fa-motorcycle fa-3x text-warning"></i>
            </div>
            <h5 class="card-title fw-bold mb-3">Delivery - Gestión</h5>
            <p class="card-text text-muted mb-4">
                Gestión y seguimiento de pedidos delivery.
            </p>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.delivery.index') }}" class="btn btn-warning">
                    <i class="fas fa-cash-register me-2"></i>Gestionar Delivery
                </a>
            </div>
        </div>
    </div>
</div>
@endif



        <!-- Mesas -->
        <div class="col-lg-3 col-md-4">
            <div class="card h-100 shadow border-0">
                <div class="card-body text-center p-4">
                    <div class="service-icon mb-3">
                        <i class="fas fa-chair fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-3">Mesas</h5>
                    <p class="card-text text-muted mb-4">Gestión de mesas y su disponibilidad.</p>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>Ver Lista
                        </a>
                        <a href="#" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Registrar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categorías -->
        <div class="col-lg-3 col-md-4">
            <div class="card h-100 shadow border-0">
                <div class="card-body text-center p-4">
                    <div class="service-icon mb-3">
                        <i class="fas fa-folder-open fa-3x text-warning"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-3">Categorías</h5>
                    <p class="card-text text-muted mb-4">Clasificación de platos: entradas, postres, bebidas...</p>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-warning">
                            <i class="fas fa-list me-2"></i>Ver Lista
                        </a>
                        <a href="#" class="btn btn-warning text-white">
                            <i class="fas fa-plus me-2"></i>Registrar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Platos -->
        <div class="col-lg-3 col-md-4">
            <div class="card h-100 shadow border-0">
                <div class="card-body text-center p-4">
                    <div class="service-icon mb-3">
                        <i class="fas fa-utensils fa-3x text-success"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-3">Platos</h5>
                    <p class="card-text text-muted mb-4">Administración del menú con precios y disponibilidad.</p>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-success">
                            <i class="fas fa-list me-2"></i>Ver Lista
                        </a>
                        <a href="#" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Registrar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pedidos -->
        <div class="col-lg-3 col-md-4">
            <div class="card h-100 shadow border-0">
                <div class="card-body text-center p-4">
                    <div class="service-icon mb-3">
                        <i class="fas fa-receipt fa-3x text-danger"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-3">Pedidos</h5>
                    <p class="card-text text-muted mb-4">Gestión de pedidos por mesa o cliente.</p>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-danger">
                            <i class="fas fa-list me-2"></i>Ver Lista
                        </a>
                        <a href="#" class="btn btn-danger">
                            <i class="fas fa-plus me-2"></i>Registrar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clientes -->
        <div class="col-lg-3 col-md-4">
            <div class="card h-100 shadow border-0">
                <div class="card-body text-center p-4">
                    <div class="service-icon mb-3">
                        <i class="fas fa-users fa-3x text-info"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-3">Clientes</h5>
                    <p class="card-text text-muted mb-4">Registro de clientes para pedidos y fidelización.</p>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-info">
                            <i class="fas fa-list me-2"></i>Ver Lista
                        </a>
                        <a href="#" class="btn btn-info text-white">
                            <i class="fas fa-plus me-2"></i>Registrar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ventas -->
        <div class="col-lg-3 col-md-4">
            <div class="card h-100 shadow border-0">
                <div class="card-body text-center p-4">
                    <div class="service-icon mb-3">
                        <i class="fas fa-cash-register fa-3x text-secondary"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-3">Ventas</h5>
                    <p class="card-text text-muted mb-4">Control y registro de ventas diarias.</p>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>Ver Lista
                        </a>
                        <a href="#" class="btn btn-secondary">
                            <i class="fas fa-plus me-2"></i>Registrar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proveedores -->
        <div class="col-lg-3 col-md-4">
            <div class="card h-100 shadow border-0">
                <div class="card-body text-center p-4">
                    <div class="service-icon mb-3">
                        <i class="fas fa-truck fa-3x text-dark"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-3">Proveedores</h5>
                    <p class="card-text text-muted mb-4">Gestión de proveedores y abastecimiento.</p>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-dark">
                            <i class="fas fa-list me-2"></i>Ver Lista
                        </a>
                        <a href="#" class="btn btn-dark">
                            <i class="fas fa-plus me-2"></i>Registrar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
