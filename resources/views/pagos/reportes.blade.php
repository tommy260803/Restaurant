@extends('layouts.plantilla')

@section('contenido')
<div class="container-fluid px-4 py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="text-dark fw-bold mb-1">
                        <i class="ri-bar-chart-2-line text-primary me-2"></i>
                        Reportes Financieros
                    </h2>
                    <p class="text-muted mb-0">Pagos completados en el sistema de registro civil</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div id="mensaje" class="alert alert-success alert-dismissible fade show mb-4 border-0 rounded-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="ri-check-circle-line text-success me-2 fs-5"></i>
                <div><strong>¡Éxito!</strong> {{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="card-title mb-0 text-dark">
                        <i class="ri-file-list-3-line me-2"></i>
                        Pagos Completados
                    </h5>
                </div>
                <div class="col-auto">
                    <span class="badge bg-light text-dark px-3 py-2">
                        {{ $pagos->total() }} {{ $pagos->total() == 1 ? 'pago' : 'pagos' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th><i class="ri-hashtag me-1"></i>ID</th>
                            <th><i class="ri-user-line me-1"></i>DNI</th>
                            <th><i class="ri-mail-line me-1"></i>Correo</th>
                            <th><i class="ri-file-text-line me-1"></i>Tipo de Acta</th>
                            <th><i class="ri-money-dollar-circle-line me-1"></i>Monto</th>
                            <th><i class="ri-bank-card-line me-1"></i>Método</th>
                            <th><i class="ri-exchange-line me-1"></i>Transacción</th>
                            <th><i class="ri-calendar-line me-1"></i>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pagos as $pago)
                            <tr>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                                        #{{ str_pad($pago->id_pago, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="ri-user-3-line text-muted me-2"></i>
                                        <span>{{ $pago->DNI ?? 'Sin DNI' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="ri-at-line text-muted me-2"></i>
                                        <span>{{ $pago->Correo ?? 'Sin correo' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @switch($pago->tipo_acta)
                                            @case('acta_nacimiento') 
                                                <i class="ri-heart-line text-success me-2"></i>
                                                <span class="badge bg-success-subtle text-success">Nacimiento</span>
                                                @break
                                            @case('acta_matrimonio') 
                                                <i class="ri-heart-2-line text-danger me-2"></i>
                                                <span class="badge bg-danger-subtle text-danger">Matrimonio</span>
                                                @break
                                            @case('acta_defuncion') 
                                                <i class="ri-cross-line text-dark me-2"></i>
                                                <span class="badge bg-dark-subtle text-dark">Defunción</span>
                                                @break
                                            @default 
                                                <i class="ri-file-text-line text-info me-2"></i>
                                                <span class="badge bg-info-subtle text-info">{{ ucfirst(str_replace('_', ' ', $pago->tipo_acta)) }}</span>
                                        @endswitch
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="ri-coins-line text-warning me-2"></i>
                                        <strong class="text-success">S/. {{ number_format($pago->monto, 2) }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if(strtolower($pago->metodo_pago) == 'efectivo')
                                            <i class="ri-money-dollar-box-line text-success me-2"></i>
                                        @elseif(strtolower($pago->metodo_pago) == 'tarjeta')
                                            <i class="ri-bank-card-2-line text-primary me-2"></i>
                                        @else
                                            <i class="ri-smartphone-line text-info me-2"></i>
                                        @endif
                                        <span>{{ ucfirst($pago->metodo_pago) }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($pago->num_transaccion)
                                        <div class="d-flex align-items-center">
                                            <i class="ri-qr-code-line text-muted me-2"></i>
                                            <code>{{ $pago->num_transaccion }}</code>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="ri-time-line text-muted me-2"></i>
                                        <span>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="ri-file-list-line fs-1 text-muted mb-3"></i>
                                    <div>No se encontraron pagos completados.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Estadísticas de Pagos -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary-subtle">
                <div class="card-body text-center">
                    <i class="ri-money-dollar-circle-line text-primary fs-1 mb-3"></i>
                    <h4 class="text-primary mb-1">S/. {{ number_format($totalPagos ?? 0, 2) }}</h4>
                    <p class="text-muted mb-0">Total de Pagos</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success-subtle">
                <div class="card-body text-center">
                    <i class="ri-check-double-line text-success fs-1 mb-3"></i>
                    <h4 class="text-success mb-1">{{ $pagosCompletados ?? 0 }}</h4>
                    <p class="text-muted mb-0">Pagos Completados</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-info-subtle">
                <div class="card-body text-center">
                    <i class="ri-calendar-check-line text-info fs-1 mb-3"></i>
                    <h4 class="text-info mb-1">S/. {{ number_format($totalMes ?? 0, 2) }}</h4>
                    <p class="text-muted mb-0">Total del Mes</p>
                </div>
            </div>
        </div>
    </div>

    @if($pagos->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $pagos->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>

<script>
    setTimeout(() => {
        const mensaje = document.getElementById('mensaje');
        if (mensaje) {
            mensaje.classList.add('fade');
            setTimeout(() => mensaje.remove(), 500);
        }
    }, 5000);
</script>

<style>
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    .badge {
        font-size: 0.8rem;
    }
    .card-body {
        transition: transform 0.2s ease;
    }
    .card:hover .card-body {
        transform: translateY(-2px);
    }
</style>
@endsection