@extends('layouts.plantillaPago')

@section('contenido')
    <div class="container py-4">
        <!-- Título principal -->
        <h1 class="h4 mb-2 text-primary fw-bold">
            <i class="bi bi-file-earmark-text me-2"></i>
            Copias certificadas de actas/partidas
        </h1>
        <p class="text-muted mb-3">Servicio en Línea</p>

        <!-- Estimado usuario -->
        <div class="mb-3">
            <h2 class="h6 text-dark">
                <i class="bi bi-person-lines-fill me-2"></i> Estimado(a) {{ Auth::user()->name ?? 'USUARIO' }}:
            </h2>
            <p class="text-muted small mb-0">
                Su pago ha sido procesado correctamente. A continuación, se detallan los datos del comprobante.
            </p>
        </div>

        <!-- Paso 5 de 6 -->
        <div class="d-flex align-items-start bg-success bg-opacity-10 border rounded p-3 mb-4">
            <div class="me-3 text-success fs-4">
                <i class="bi bi-check-circle"></i>
            </div>
            <div>
                <h6 class="mb-1 text-dark">Pago Confirmado</h6>
                <small class="text-muted mb-1 d-block">Paso 5 de 5</small>
                <p class="mb-0 text-muted small">
                    El comprobante será enviado automáticamente a su correo registrado.
                </p>
            </div>
        </div>

        <!-- Tarjeta con resumen del pago -->
        <div class="bg-white border rounded shadow-sm p-4 mb-4">
            <div class="row g-4">
                <!-- Columna izquierda -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <small class="text-muted">Nombre del Usuario:</small>
                        <div class="fw-semibold text-dark">USUARIO</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">DNI:</small>
                        <div class="fw-semibold text-dark">{{ session('dni') ?? 'No disponible' }}</div>
                    </div>
                    <small class="text-muted">Correo electrónico:</small>
                    <div class="fw-semibold text-dark">{{ session('correo') ?? 'Correo no disponible' }}</div>
                </div>

                <!-- Columna derecha -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <small class="text-muted">Tipo de Acta:</small>
                        <div class="fw-semibold text-dark">{{ ucfirst(str_replace('_', ' ', $tipo_acta)) }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Número de Acta:</small>
                        <div class="fw-semibold text-dark">{{ $id_acta }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Monto Pagado:</small>
                        <div class="fw-bold text-success">S/ {{ number_format($monto, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensaje final -->
        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-patch-check-fill me-2 fs-5"></i>
            ¡Gracias por utilizar nuestro servicio! Su solicitud ha sido registrada con éxito.
        </div>

        <!-- Botón -->
        <form method="POST" action="{{ route('pago.guardar') }}">
            @csrf
            <input type="hidden" name="id_acta" value="{{ $id_acta }}">
            <input type="hidden" name="tipo_acta" value="{{ $tipo_acta }}">
            <input type="hidden" name="monto" value="{{ $monto }}">
            <input type="hidden" name="metodo_pago" value="Banco de la Nacion">
            <input type="hidden" name="num_transaccion" value="{{ $num_transaccion }}">

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                    Volver al inicio
                    <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </form>

    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const volverBtn = document.querySelector('a[href="{{ route('login') }}"]');
            const loader = document.getElementById("googleLoader");
            const progress = loader?.querySelector('.loader-progress');

            if (volverBtn && loader && progress) {
                volverBtn.addEventListener('click', function (e) {
                    e.preventDefault(); // Evita redirección inmediata

                    // Mostrar loader
                    loader.style.display = "block";
                    progress.style.width = "0%";
                    progress.style.animation = "none";
                    void progress.offsetWidth;
                    progress.style.animation = "loadBar 2s linear forwards";

                    // Redirigir después de una pequeña espera
                    setTimeout(() => {
                        window.location.href = volverBtn.href;
                    }, 1000); // Puedes cambiar 1000 ms por 1500 ms si deseas más tiempo visual
                });
            }
        });
    </script>
@endsection