<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicio RENIEC</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        .loader-bar {
            height: 5px;
            width: 100%;
            background-color: #e0e0e0;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 10;
            display: none;
        }

        .loader-progress {
            height: 100%;
            width: 0%;
            background-color: #0d6efd;
            animation: loadBar 2s linear forwards;
        }

        @keyframes loadBar {
            0% {
                width: 0%;
            }

            100% {
                width: 100%;
            }
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100 bg-light">

    <div class="bg-white shadow w-100 text-dark position-relative"
        style="max-width: 1140px; height: 650px; border-radius: 35px; overflow: hidden;">

        <!-- Loader barra superior -->
        <div id="googleLoader" class="loader-bar">
            <div class="loader-progress"></div>
        </div>

        <!-- Área scrollable centrada vertical y horizontalmente -->
        <div class="h-100 w-100 overflow-auto">
            <div class="d-flex justify-content-center align-items-center" style="min-height: 600px;">
                <div class="w-100 px-4" style="max-width: 900px; margin-bottom: 70px; margin-top: 20px;">
                    @yield('contenido')
                </div>
            </div>
        </div>

        <!-- Footer fijo abajo del div principal -->
        <div class="position-absolute bottom-0 start-0 end-0 bg-primary text-white px-5 py-3 small">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <p class="mb-1 mb-md-0">
                    <i class="bi bi-telephone me-1"></i>
                    Línea gratuita: <strong>Aló RENIEC 0800-11040</strong>
                </p>
                <p class="mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Este servicio es aplicable únicamente a personas mayores de 18 años.
                </p>
            </div>
        </div>
    </div>

    @if (session('error'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Cerrar'
                });
            });
        </script>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const loader = document.getElementById("googleLoader");
            const progress = loader?.querySelector('.loader-progress');

            function startLoader() {
                if (loader && progress) {
                    loader.style.display = "block";
                    progress.style.width = "0%";
                    progress.style.animation = "none";
                    void progress.offsetWidth; // Forzar reflujo
                    progress.style.animation = "loadBar 2s linear forwards";
                }
            }

            function stopLoader() {
                if (loader && progress) {
                    loader.style.display = "none";
                    progress.style.width = "0%";
                    progress.style.animation = "none";
                }
            }

            // Mostrar loader en envío de formularios
            document.querySelectorAll("form").forEach(form => {
                form.addEventListener("submit", function() {
                    startLoader();
                });
            });

            // Mostrar loader al hacer clic en enlaces internos
            document.querySelectorAll("a[href]").forEach(link => {
                const href = link.getAttribute("href");

                // Evitar activar en anchors, JavaScript void o externos
                if (
                    href &&
                    !href.startsWith("#") &&
                    !href.startsWith("javascript:") &&
                    !link.hasAttribute("target")
                ) {
                    link.addEventListener("click", function () {
                        startLoader();
                    });
                }
            });

            // Ocultar loader cuando vuelves con el botón atrás
            window.addEventListener("pageshow", function(event) {
                stopLoader();
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
