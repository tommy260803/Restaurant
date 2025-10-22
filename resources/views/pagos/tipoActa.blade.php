@extends('layouts.plantillaPago')

@section('contenido')
    <div class="container py-4">

        <div class="mb-4">
            <h5 class="text-dark"><i class="bi bi-person-circle me-2"></i>Estimado Usuario:</h5>
            <p class="text-muted mb-0">¿Los datos mostrados son los correctos?</p>
        </div>

        <!-- Paso proceso -->
        <div class="d-flex bg-light border rounded p-3 mb-4 align-items-start">
            <div class="me-3 text-primary fs-4">
                <i class="bi bi-shield-check"></i>
            </div>
            <div>
                <h6 class="mb-1 text-dark">Verificación de Datos</h6>
                <small class="text-muted mb-1 d-block">Paso 3 de 5</small>
                <p class="mb-0 text-muted small">
                    Revise que los datos ingresados sean correctos antes de continuar con el proceso.
                </p>
            </div>
        </div>

        <!-- Sección con datos -->
        <div class="bg-white rounded shadow-sm p-4">
            <div class="row g-4">
                <!-- Número de Acta -->
                <div class="col-md-6 d-flex align-items-start border-bottom pb-3">
                    <div class="text-primary me-3">
                        <i class="bi bi-file-earmark-text fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted">Número de Acta / Partida</small>
                        <div class="fw-semibold text-dark">{{ $id_acta }}</div>
                    </div>
                </div>

                <!-- Fecha -->
                <div class="col-md-6 d-flex align-items-start border-bottom pb-3">
                    <div class="text-primary me-3">
                        <i class="bi bi-calendar-date fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted">Fecha de Solicitud</small>
                        <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($fecha_registro)->format('d/m/Y') }}
                        </div>
                    </div>
                </div>

                <!-- Tipo de Acta -->
                <div class="col-md-6 d-flex align-items-start border-bottom pb-3">
                    <div class="text-primary me-3">
                        <i class="bi bi-list-task fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted">Tipo de Acta</small>
                        <div class="fw-semibold text-dark text-capitalize">{{ str_replace('_', ' ', $tipo) }}</div>
                    </div>
                </div>

                <!-- Titular -->
                <div class="col-md-6 d-flex align-items-start border-bottom pb-3">
                    <div class="text-primary me-3">
                        <i class="bi bi-person-lines-fill fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted">Titular del Acta</small>
                        <div class="fw-semibold text-dark">{{ $titular ?? 'INFORMACIÓN CONFIDENCIAL' }}</div>
                    </div>
                </div>

                <!-- Solicitante -->
                <div class="col-md-6 d-flex align-items-start border-bottom pb-3">
                    <div class="text-primary me-3">
                        <i class="bi bi-person-circle fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted">Solicitante</small>
                         @if(isset($solicitante))
                            <p class="text-lg font-semibold text-gray-800">{{ $solicitante }} - {{ session('dni') }}</p>
                        @else
                            <p class="text-lg font-semibold text-gray-800">Usuario - {{ session('dni') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-end gap-3 pt-4 mt-4 border-top">
                <button type="button" class="btn btn-outline-secondary d-flex align-items-center"
                    onclick="window.history.back()">
                    <i class="bi bi-arrow-left me-2"></i> Cancelar
                </button>
                <a href="{{ route('pagos.pagoActa', ['id' => $id_acta, 'tipo' => $tipo]) }}"
                    class="btn btn-warning text-white d-flex align-items-center"
                    id="btnIniciarPago">
                    <i class="bi bi-credit-card-2-back me-2"></i> Iniciar Pago
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const btnIniciar = document.getElementById("btnIniciarPago");
            const loader = document.getElementById("googleLoader");
            const progress = loader?.querySelector('.loader-progress');

            if (btnIniciar) {
                btnIniciar.addEventListener("click", function (e) {
                    e.preventDefault(); // Evita la redirección inmediata

                    if (loader && progress) {
                        loader.style.display = "block";
                        progress.style.width = "0%";
                        progress.style.animation = "none";
                        void progress.offsetWidth;
                        progress.style.animation = "loadBar 2s linear forwards";
                    }

                    // Redirige luego de 2 segundos
                    setTimeout(() => {
                        window.location.href = btnIniciar.href;
                    }, 2000);
                });
            }
        });
    </script>
@endsection
