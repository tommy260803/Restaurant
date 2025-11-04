{{-- Paso 3: Selección de Mesa --}}
<h4 class="mb-4 text-center">
    <i class="bi bi-grid-3x3-gap"></i> Selecciona tu Mesa
</h4>
<p class="text-center text-muted mb-4">Elige la mesa que prefieras según tu grupo</p>

<!-- Leyenda de Estados -->
<div class="d-flex justify-content-center gap-3 mb-4 p-3 bg-light rounded">
    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Disponible</span>
    <span class="badge bg-warning"><i class="bi bi-exclamation-circle"></i> Reservada</span>
    <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ocupada</span>
</div>

<!-- Grid de Mesas -->
<div class="row g-3">
    @foreach($mesas as $mesa)
    <div class="col-6 col-md-4 col-lg-3">
        <div class="mesa-card {{ $mesa->estado }}"
             :class="{ 'selected': formData.mesa_id === {{ $mesa->id }} }"
             @click="if('{{ $mesa->estado }}' === 'disponible') formData.mesa_id = {{ $mesa->id }}">
            
            <div class="mesa-icon">
                <i class="bi bi-table"></i>
            </div>
            
            <div class="mesa-number">
                Mesa {{ $mesa->numero }}
            </div>
            
            <div class="text-muted small">
                <i class="bi bi-people-fill"></i> {{ $mesa->capacidad }} personas
            </div>
            
            <div class="mt-2">
                @if($mesa->estado === 'disponible')
                    <span class="badge bg-success">Disponible</span>
                @elseif($mesa->estado === 'reservada')
                    <span class="badge bg-warning">Reservada</span>
                @else
                    <span class="badge bg-danger">Ocupada</span>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Mensaje de Mesa Seleccionada -->
<div class="alert alert-info mt-4" x-show="formData.mesa_id">
    <i class="bi bi-check-circle"></i>
    <strong>Mesa seleccionada:</strong> Mesa #<span x-text="formData.mesa_id"></span>
</div>

<style>
    .mesa-card {
        padding: 20px;
        border: 3px solid #dee2e6;
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        min-height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .mesa-card.disponible {
        border-color: #28a745;
        background: #d4edda;
    }

    .mesa-card.disponible:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }

    .mesa-card.reservada {
        border-color: #ffc107;
        background: #fff3cd;
    }

    .mesa-card.ocupada {
        border-color: #dc3545;
        background: #f8d7da;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .mesa-card.selected {
        border-color: #0d6efd;
        background: #cfe2ff;
        transform: scale(1.05);
        box-shadow: 0 0 15px rgba(13, 110, 253, 0.5);
    }

    .mesa-icon {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .mesa-number {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 5px;
    }
</style>
