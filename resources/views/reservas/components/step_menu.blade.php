{{-- Paso 4: Pre-orden de Menú (Opcional) --}}
<h4 class="mb-4 text-center">
    <i class="bi bi-basket"></i> Pre-ordena tu Comida (Opcional)
</h4>
<p class="text-center text-muted mb-4">Puedes ordenar ahora o al llegar al restaurante</p>

<!-- Grid de Platos -->
<div class="row g-3 mb-4">
    @foreach($platos as $plato)
    <div class="col-md-6 col-lg-4">
        <div class="card plato-card h-100">
            @if($plato->imagen)
            <img src="{{ asset('storage/' . $plato->imagen) }}" 
                 class="card-img-top" 
                 style="height: 200px; object-fit: cover;" 
                 alt="{{ $plato->nombre }}">
            @else
            <div class="bg-secondary" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-image text-white" style="font-size: 3rem;"></i>
            </div>
            @endif
            
            <div class="card-body">
                <h6 class="card-title">{{ $plato->nombre }}</h6>
                <p class="card-text small text-muted">{{ Str::limit($plato->descripcion, 60) }}</p>
                
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-primary">S/ {{ number_format($plato->precio, 2) }}</span>
                    
                    <div class="btn-group">
                        <button type="button" 
                                class="btn btn-sm btn-outline-secondary"
                                @click="decrementPlato({{ $plato->idPlatoProducto }})">
                            <i class="bi bi-dash"></i>
                        </button>
                        
                        <span class="btn btn-sm btn-outline-secondary" 
                              x-text="getPlatoCantidad({{ $plato->idPlatoProducto }})">0</span>
                        
                        <button type="button" 
                                class="btn btn-sm btn-outline-secondary"
                                @click="incrementPlato({{ $plato->idPlatoProducto }}, '{{ $plato->nombre }}', {{ $plato->precio }})">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Resumen del carrito -->
<div class="alert alert-success" x-show="formData.platos.length > 0">
    <h6><i class="bi bi-cart-check"></i> Tu pedido:</h6>
    <template x-for="plato in formData.platos" :key="plato.id">
        <div class="d-flex justify-content-between">
            <span><span x-text="plato.cantidad"></span>x <span x-text="plato.nombre"></span></span>
            <span>S/ <span x-text="(plato.cantidad * plato.precio).toFixed(2)"></span></span>
        </div>
    </template>
    <hr>
    <div class="d-flex justify-content-between fw-bold">
        <span>Subtotal:</span>
        <span>S/ <span x-text="calcularSubtotal().toFixed(2)"></span></span>
    </div>
</div>

<div class="text-center text-muted">
    <small><i class="bi bi-info-circle"></i> Puedes saltar este paso si prefieres ordenar al llegar</small>
</div>

<style>
    .plato-card {
        transition: all 0.3s ease;
    }

    .plato-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
