@extends('layouts.plantilla')

@section('title', 'Reportes y Métricas')

@section('contenido')
<div class="container-fluid px-4 py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="text-dark fw-bold mb-1">
                        <i class="bi bi-graph-up text-primary me-2"></i>
                        Reportes
                    </h2>
                    <p class="text-muted mb-0">Resumen de pagos, reservas y clientes</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="ri-bar-chart-2-line me-2"></i>
                        Ingresos - últimos 30 días
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartIngresosDia"></canvas>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="ri-calendar-event-line me-2"></i>
                        Reservas - últimos 30 días
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartReservasDia"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="ri-pie-chart-line me-2"></i>
                        Ingresos por método
                    </h5>
                </div>
                <div class="card-body text-center">
                    <canvas id="chartIngresosMetodo"></canvas>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="ri-user-3-line me-2"></i>
                        Top clientes (por monto)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <ul id="top-clientes" class="list-group list-group-flush"></ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.REPORTES_URLS = {
        pagosPorDia: "{{ route('ordenes.reportes.pagos_por_dia') }}",
        reservasPorDia: "{{ route('ordenes.reportes.reservas_por_dia') }}",
        pagosPorMetodo: "{{ route('ordenes.reportes.pagos_por_metodo') }}",
        topClientes: "{{ route('ordenes.reportes.top_clientes') }}"
    };
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/reportes.js') }}"></script>

<style>
    .card-body {
        min-height: 220px;
    }
    .list-group-item {
        background-color: transparent;
        border: 0;
        color: #333;
    }
    @media (max-width: 767px) {
        .card-body { min-height: 180px; }
    }
</style>
@endpush
