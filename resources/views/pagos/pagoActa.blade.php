@extends('layouts.plantillaPago')

@section('contenido')
    <div class="container py-4">

        <!-- Título principal -->
        <h1 class="h4 mb-2 text-primary fw-bold">
            <i class="bi bi-credit-card-2-back me-2"></i>
            Copias certificadas de actas/partidas
        </h1>
        <p class="text-muted mb-3">Servicio en Línea</p>

        <!-- Estimado Usuario -->
        <div class="mb-3">
            <h2 class="h6 text-dark">
                <i class="bi bi-person-lines-fill me-2"></i> Estimado(a) {{ Auth::user()->name ?? 'USUARIO' }}:
            </h2>
            <p class="text-muted small mb-0">
                Usted está a punto de realizar el pago para obtener su acta. Por favor verifique los datos y complete el
                número de transacción.
            </p>
        </div>

        <!-- Paso proceso -->
        <div class="d-flex bg-light border rounded p-3 mb-4 align-items-start">
            <div class="me-3 text-primary fs-4">
                <i class="bi bi-credit-card-2-front"></i>
            </div>
            <div>
                <h6 class="mb-1 text-dark">Zona de Pago</h6>
                <small class="text-muted mb-1 d-block">Paso 4 de 5</small>
                <p class="mb-0 text-muted small">Ingrese el número de transacción correspondiente al banco para confirmar su
                    pago.</p>
            </div>
        </div>

        <!-- Sección con tarjeta -->
        <div class="bg-white rounded shadow-sm p-4">

            <!-- Datos del acta -->
            <div class="bg-primary bg-opacity-10 p-4 rounded mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 text-muted">Tipo de Acta:</p>
                    <h6 class="mb-0 text-dark">{{ ucfirst(str_replace('_', ' ', $tipo_acta)) }}</h6>
                </div>
                <div class="text-end">
                    <p class="mb-1 text-muted">Monto a Pagar:</p>
                    <h5 class="text-primary fw-bold">S/ {{ number_format($tarifa->monto, 2) }}</h5>
                </div>
            </div>

            <!-- Formulario de pago -->
            <form id="formPago" action="{{ route('confirmarPago') }}" method="POST">
                @csrf

                <!-- Campos ocultos -->
                <input type="hidden" name="id_acta" value="{{ $id_acta }}">
                <input type="hidden" name="monto" value="{{ $tarifa->monto }}">
                <input type="hidden" name="tipo_acta" value="{{ $tipo_acta }}">
                <input type="hidden" name="metodo_pago" value="Banco de la Nación">

                <!-- Tabla método de pago -->
                <div class="row fw-semibold text-muted small border-bottom pb-2 mb-3">
                    <div class="col-md-3">Banco</div>
                    <div class="col-md-3">Número de Transacción</div>
                    <div class="col-md-2">Fecha</div>
                    <div class="col-md-2">Importe (S/.)</div>
                    <div class="col-md-2 text-center">Seleccione</div>
                </div>

                <div class="row align-items-center mb-3 border-bottom py-3 hover-shadow-sm">
                    <div class="col-md-3 d-flex align-items-center">
                        <img src="https://media.licdn.com/dms/image/v2/C560BAQHBT5pnyDc86g/company-logo_200_200/company-logo_200_200/0/1630578718854/bancodelanacionperu_logo?e=2147483647&v=beta&t=ckv8R3fDbrpMfJuJaDlqFBooIW96huZhzft8x0FlX_c"
                            alt="Banco de la Nación" class="rounded me-2"
                            style="width: 40px; height: 40px; object-fit: contain;">
                        <span class="fw-semibold text-dark">Banco de la Nación</span>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="numero_transaccion" id="numero_transaccion"
                            class="form-control form-control-sm" placeholder="Ingrese número" required>
                    </div>
                    <div class="col-md-2 text-muted">{{ date('d/m/Y') }}</div>
                    <div class="col-md-2 fw-bold text-dark">{{ number_format($tarifa->monto, 2) }}</div>
                    <div class="col-md-2 text-center">
                        <input type="radio" name="metodo_pago_radio" value="Banco de la Nación" class="form-check-input"
                            checked>
                    </div>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-end gap-3 pt-4 border-top mt-4">
                    <button type="button" onclick="window.history.back()"
                        class="btn btn-outline-secondary d-flex align-items-center">
                        <i class="bi bi-arrow-left me-2"></i> Cancelar
                    </button>
                    <button type="submit" id="btnContinuar" class="btn btn-warning text-white d-flex align-items-center">
                        <i class="bi bi-cash-stack me-2"></i> Confirmar Pago
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('formPago');
            const numeroTransaccion = document.getElementById('numero_transaccion');
            const btnContinuar = document.getElementById('btnContinuar');

            numeroTransaccion.addEventListener('input', function () {
                btnContinuar.disabled = this.value.trim().length === 0;
            });

            form.addEventListener('submit', function (e) {
                const num = numeroTransaccion.value.trim();

                if (num.length < 6) {
                    e.preventDefault();
                    alert('El número de transacción debe tener al menos 6 caracteres');
                    numeroTransaccion.focus();
                    return;
                }

                btnContinuar.disabled = true;
                btnContinuar.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Procesando...
                `;
            });

            btnContinuar.disabled = true;
        });
    </script>
@endsection
