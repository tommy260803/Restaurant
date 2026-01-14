@extends('layouts.plantilla')

@section('title', 'Reportes y Métricas')

@section('contenido')
<div class="container-fluid py-4" style="background-color: #1a1a2e; min-height: 100vh;">
    <div class="row">
        <div class="col-12 mb-3">
            <h3 class="text-white">Reportes</h3>
            <p class="text-muted">Resumen de pagos, reservas y clientes</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3" style="background-color: #2d2d44; border: 1px solid #3a3a54;">
                <div class="card-body">
                    <h5 class="text-white">Ingresos - últimos 30 días</h5>
                    <canvas id="chartIngresosDia"></canvas>
                </div>
            </div>

            <div class="card mb-3" style="background-color: #2d2d44; border: 1px solid #3a3a54;">
                <div class="card-body">
                    <h5 class="text-white">Reservas - últimos 30 días</h5>
                    <canvas id="chartReservasDia"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3" style="background-color: #2d2d44; border: 1px solid #3a3a54;">
                <div class="card-body">
                    <h5 class="text-white">Ingresos por método</h5>
                    <canvas id="chartIngresosMetodo"></canvas>
                </div>
            </div>

            <div class="card mb-3" style="background-color: #2d2d44; border: 1px solid #3a3a54;}">
                <div class="card-body">
                    <h5 class="text-white">Top clientes (por monto)</h5>
                    <ul id="top-clientes" class="list-group list-group-flush"></ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/reportes.js') }}"></script>
@endpush
