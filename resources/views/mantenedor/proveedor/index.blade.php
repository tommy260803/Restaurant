{{-- filepath: resources/views/proveedores/index.blade.php --}}
@extends('layouts.plantilla')

@section('titulo', 'Gestión de Proveedores')

@section('contenido')
<div class="container-fluid mt-3 px-4 animate__animated animate__fadeIn restaurant-theme">

    {{-- Header con diseño de restaurante --}}
    <div class="mb-5">
        <div class="restaurant-header p-4 rounded-4 mb-4 position-relative overflow-hidden">
            <div class="restaurant-pattern"></div>
            <div class="row align-items-center position-relative">
                <div class="col-md-8">
                    <h1 class="restaurant-title mb-3">
                        <span class="chef-icon">👨‍🍳</span>
                        Nuestros Proveedores
                    </h1>
                    <p class="restaurant-subtitle mb-0">Socios estratégicos que nos ayudan a crear experiencias culinarias excepcionales</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="restaurant-stats">
                        <div class="stat-item">
                            <span class="stat-number">{{ $proveedores->total() }}</span>
                            <span class="stat-label">Proveedores</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Botones de acción con estilo restaurante --}}
        <div class="d-flex flex-wrap gap-3 justify-content-center justify-md-end mb-4">
            <a href="{{ route('proveedor.exportarPDF') }}" 
               class="btn-restaurant btn-restaurant-red" target="_blank">
                <i class="fas fa-file-pdf"></i>
                <span>Descargar PDF</span>
            </a>
            <a href="{{ route('proveedor.exportarExcel') }}" 
               class="btn-restaurant btn-restaurant-green" target="_blank">
                <i class="fas fa-file-excel"></i>
                <span>Descargar Excel</span>
            </a>
            <a href="{{ route('proveedor.create') }}" 
               class="btn-restaurant btn-restaurant-primary">
                <i class="fas fa-plus-circle"></i>
                <span>Nuevo Proveedor</span>
            </a>
        </div>
    </div>

    {{-- Filtros con diseño moderno --}}
    <div class="restaurant-filters mb-5">
        <form method="GET" class="d-flex flex-wrap gap-3 justify-content-center justify-md-end">
            <div class="search-group">
                <i class="fas fa-search search-icon"></i>
                <input name="buscar" type="search" placeholder="Buscar proveedor..." 
                       class="form-control-restaurant" value="{{ $buscar ?? '' }}">
            </div>
            <div class="filter-group">
                <select name="estado" class="form-select-restaurant">
                    <option value="">🍽️ Todos los estados</option>
                    <option value="activo" {{ ($estado ?? '') == 'activo' ? 'selected' : '' }}>✅ Activo</option>
                    <option value="inactivo" {{ ($estado ?? '') == 'inactivo' ? 'selected' : '' }}>⏸️ Inactivo</option>
                    <option value="bloqueado" {{ ($estado ?? '') == 'bloqueado' ? 'selected' : '' }}>🚫 Bloqueado</option>
                </select>
            </div>
            <button type="submit" class="btn-restaurant btn-restaurant-search">
                <i class="fas fa-filter"></i>
                Filtrar
            </button>
        </form>
    </div>

    {{-- Mensaje de éxito con estilo restaurante --}}
    @if (session('success'))
        <div id="mensaje" class="alert-restaurant alert-success mb-4">
            <i class="fas fa-check-circle"></i>
            <strong>¡Perfecto!</strong> {{ session('success') }}
        </div>
    @endif

    {{-- Cards de proveedores estilo restaurante --}}
    <div class="row g-4">
        @forelse($proveedores as $item)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="provider-card">
                    <div class="card-header">
                        <div class="provider-avatar">
                            <span>{{ strtoupper(substr($item->nombre, 0, 2)) }}</span>
                        </div>
                        <div class="provider-info">
                            <h5 class="provider-name">{{ $item->nombre }} {{ $item->apellidoPaterno }}</h5>
                            <p class="provider-subtitle">{{ $item->apellidoMaterno }}</p>
                        </div>
                        <div class="provider-status">
                            @if ($item->estado === 'activo')
                                <span class="badge-restaurant badge-success">🟢 Activo</span>
                            @elseif ($item->estado === 'bloqueado')
                                <span class="badge-restaurant badge-danger">🔴 Bloqueado</span>
                            @else
                                <span class="badge-restaurant badge-secondary">⚪ Inactivo</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="contact-info">
                            <div class="info-item">
                                <i class="fas fa-phone-alt"></i>
                                <span>{{ $item->telefono ?: 'No registrado' }}</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-envelope"></i>
                                <span>{{ $item->email ?: 'No registrado' }}</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-id-card"></i>
                                <span>RUC: {{ $item->ruc ?: 'No registrado' }}</span>
                            </div>
                        </div>

                        <div class="rating-section">
                            <h6 class="rating-title">🌟 Evaluación del Proveedor</h6>
                            <div class="rating-grid">
                                <div class="rating-item">
                                    <span class="rating-label">Puntualidad</span>
                                    <span class="rating-value">{{ $item->puntualidad ?? '-' }}</span>
                                </div>
                                <div class="rating-item">
                                    <span class="rating-label">Calidad</span>
                                    <span class="rating-value">{{ $item->calidad ?? '-' }}</span>
                                </div>
                                <div class="rating-item">
                                    <span class="rating-label">Precio</span>
                                    <span class="rating-value">{{ $item->precio ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-actions">
                        <a href="{{ route('proveedor.edit', $item->idProveedor) }}" 
                           class="btn-action btn-edit" title="Editar información">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('proveedor.dashboard', $item->idProveedor) }}" 
                           class="btn-action btn-dashboard" title="Ver estadísticas">
                            <i class="fas fa-chart-pie"></i>
                        </a>
                        <a href="{{ route('proveedor.historial', $item->idProveedor) }}" 
                           class="btn-action btn-history" title="Ver historial">
                            <i class="fas fa-history"></i>
                        </a>
                        <a href="#" onclick="mostrarModalCalificar({{ $item->idProveedor }})" 
                           class="btn-action btn-rate" title="Calificar proveedor">
                            <i class="fas fa-star"></i>
                        </a>
                        @if ($item->estado === 'activo')
                            <form action="{{ route('proveedor.destroy', $item->idProveedor) }}" 
                                  method="POST" style="display: inline;"
                                  onsubmit="return confirm('¿Seguro que quieres desactivar este proveedor?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Desactivar proveedor">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <div class="empty-icon">🍽️</div>
                    <h4>No hay proveedores disponibles</h4>
                    <p>Comienza agregando tu primer proveedor para gestionar tu restaurante</p>
                    <a href="{{ route('proveedor.create') }}" class="btn-restaurant btn-restaurant-primary">
                        <i class="fas fa-plus-circle"></i>
                        <span>Agregar Primer Proveedor</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Paginación con estilo restaurante --}}
    <div class="mt-5 d-flex justify-content-center">
        <div class="pagination-restaurant">
            {{ $proveedores->links() }}
        </div>
    </div>
</div>

<style>
/* Tema principal del restaurante */
.restaurant-theme {
    background: linear-gradient(135deg, #fef7ed 0%, #fed7aa 100%);
    min-height: 100vh;
}

/* Header del restaurante */
.restaurant-header {
    background: linear-gradient(135deg, #ea580c, #dc2626);
    color: white;
    box-shadow: 0 10px 30px rgba(234, 88, 12, 0.3);
    border: 3px solid #fed7aa;
}

.restaurant-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 2px, transparent 2px),
        radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 2px, transparent 2px);
    background-size: 50px 50px;
}

.restaurant-title {
    font-size: 2.5rem;
    font-weight: 800;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    margin: 0;
}

.chef-icon {
    font-size: 3rem;
    margin-right: 15px;
    display: inline-block;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

.restaurant-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    font-weight: 300;
}

.restaurant-stats {
    text-align: center;
    background: rgba(255,255,255,0.2);
    border-radius: 15px;
    padding: 20px;
    backdrop-filter: blur(10px);
}

.stat-number {
    display: block;
    font-size: 2.5rem;
    font-weight: 900;
    line-height: 1;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Botones estilo restaurante */
.btn-restaurant {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 2px solid transparent;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-restaurant:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.btn-restaurant-primary {
    background: linear-gradient(135deg, #ea580c, #dc2626);
    color: white;
}

.btn-restaurant-red {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: white;
}

.btn-restaurant-green {
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
}

.btn-restaurant-search {
    background: linear-gradient(135deg, #7c3aed, #6366f1);
    color: white;
}

/* Filtros con estilo moderno */
.restaurant-filters {
    background: white;
    padding: 25px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border: 2px solid #fed7aa;
}

.search-group {
    position: relative;
    min-width: 280px;
}

.search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
    z-index: 2;
}

.form-control-restaurant {
    border: 2px solid #e5e7eb;
    border-radius: 25px;
    padding: 12px 15px 12px 45px;
    font-size: 0.95rem;
    transition: all 0.3s;
    background: #f9fafb;
}

.form-control-restaurant:focus {
    border-color: #ea580c;
    box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.1);
    background: white;
}

.form-select-restaurant {
    border: 2px solid #e5e7eb;
    border-radius: 25px;
    padding: 12px 20px;
    font-size: 0.95rem;
    background: #f9fafb;
    transition: all 0.3s;
    min-width: 200px;
}

.form-select-restaurant:focus {
    border-color: #ea580c;
    box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.1);
    background: white;
}

/* Cards de proveedores */
.provider-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border: 2px solid #fed7aa;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.provider-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    border-color: #ea580c;
}

.card-header {
    background: linear-gradient(135deg, #f97316, #ea580c);
    color: white;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    position: relative;
    overflow: hidden;
}

.card-header::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='3' cy='3' r='3'/%3E%3C/g%3E%3C/svg%3E");
}

.provider-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 1.5rem;
    backdrop-filter: blur(10px);
    border: 3px solid rgba(255,255,255,0.3);
    z-index: 1;
    position: relative;
}

.provider-info {
    flex: 1;
    z-index: 1;
    position: relative;
}

.provider-name {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
}

.provider-subtitle {
    font-size: 0.9rem;
    opacity: 0.9;
    margin: 0;
    font-weight: 400;
}

.provider-status {
    z-index: 1;
    position: relative;
}

.badge-restaurant {
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    border: 2px solid rgba(255,255,255,0.3);
    backdrop-filter: blur(10px);
}

.badge-success {
    background: rgba(34, 197, 94, 0.9);
    color: white;
}

.badge-danger {
    background: rgba(239, 68, 68, 0.9);
    color: white;
}

.badge-secondary {
    background: rgba(107, 114, 128, 0.9);
    color: white;
}

.card-body {
    padding: 25px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 15px;
    background: #f8fafc;
    border-radius: 12px;
    border-left: 4px solid #ea580c;
}

.info-item i {
    color: #ea580c;
    width: 20px;
    text-align: center;
}

.info-item span {
    font-size: 0.9rem;
    color: #374151;
    font-weight: 500;
}

.rating-section {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border-radius: 15px;
    padding: 20px;
    border: 2px solid #f59e0b;
}

.rating-title {
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: #92400e;
    text-align: center;
}

.rating-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}

.rating-item {
    text-align: center;
    background: rgba(255,255,255,0.7);
    border-radius: 10px;
    padding: 10px 5px;
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.rating-label {
    display: block;
    font-size: 0.75rem;
    color: #92400e;
    font-weight: 600;
    margin-bottom: 5px;
    text-transform: uppercase;
}

.rating-value {
    display: block;
    font-size: 1.1rem;
    font-weight: 900;
    color: #b45309;
}

.card-actions {
    display: flex;
    justify-content: center;
    gap: 8px;
    padding: 20px;
    background: #f8fafc;
    border-top: 2px solid #e5e7eb;
}

.btn-action {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    transition: all 0.3s;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.btn-action:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.btn-edit { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; }
.btn-dashboard { background: linear-gradient(135deg, #06b6d4, #0891b2); color: white; }
.btn-history { background: linear-gradient(135deg, #6b7280, #4b5563); color: white; }
.btn-rate { background: linear-gradient(135deg, #10b981, #059669); color: white; }
.btn-delete { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; }

/* Estado vacío */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border: 2px solid #fed7aa;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 20px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.empty-state h4 {
    color: #374151;
    font-weight: 700;
    margin-bottom: 10px;
}

.empty-state p {
    color: #6b7280;
    margin-bottom: 30px;
}

/* Alerta estilo restaurante */
.alert-restaurant {
    border-radius: 15px;
    padding: 20px;
    border: 2px solid;
    display: flex;
    align-items: center;
    gap: 15px;
    font-weight: 500;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.alert-success {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    border-color: #10b981;
    color: #047857;
}

.alert-restaurant i {
    font-size: 1.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .restaurant-title {
        font-size: 2rem;
    }
    
    .chef-icon {
        font-size: 2.5rem;
    }
    
    .btn-restaurant span {
        display: none;
    }
    
    .btn-restaurant {
        padding: 12px;
        min-width: auto;
    }
    
    .search-group {
        min-width: 100%;
    }
    
    .rating-grid {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .card-actions {
        flex-wrap: wrap;
        gap: 10px;
    }
}

/* Animaciones adicionales */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.provider-card {
    animation: fadeInUp 0.6s ease-out forwards;
}

.provider-card:nth-child(1) { animation-delay: 0.1s; }
.provider-card:nth-child(2) { animation-delay: 0.2s; }
.provider-card:nth-child(3) { animation-delay: 0.3s; }
.provider-card:nth-child(4) { animation-delay: 0.4s; }
.provider-card:nth-child(5) { animation-delay: 0.5s; }
.provider-card:nth-child(6) { animation-delay: 0.6s; }
</style>
@endsection

@section('script')
<script>
    // Remover mensaje después de 3 segundos con animación
    setTimeout(() => {
        let mensaje = document.getElementById('mensaje');
        if (mensaje) {
            mensaje.style.transition = 'all 0.5s ease-out';
            mensaje.style.transform = 'translateX(100%)';
            mensaje.style.opacity = '0';
            setTimeout(() => mensaje.remove(), 500);
        }
    }, 3000);

    // Animación de entrada progresiva para las cards
    function animateCards() {
        const cards = document.querySelectorAll('.provider-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(50px)';
            setTimeout(() => {
                card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }

    // Ejecutar animaciones cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        animateCards();
        
        // Efecto de hover mejorado para las cards
        const cards = document.querySelectorAll('.provider-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
        
        // Efecto de ripple en botones
        const buttons = document.querySelectorAll('.btn-restaurant, .btn-action');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('div');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(255,255,255,0.3);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    pointer-events: none;
                `;
                
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 600);
            });
        });
    });

    // Agregar estilos para el efecto ripple
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
</script>
@endsection