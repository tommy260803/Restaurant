@extends('layouts.plantilla')
@section('titulo', 'Mi Perfil')

@section('contenido')
    <style>
        .nav-item:hover {
            background-color: rgb(13, 110, 253, 0.5);
            border-radius: 0.5rem;
        }

        .nav-item:hover .nav-link {
            color: #000 !important;
        }

        .verde:hover {
            background-color: rgba(25, 135, 84, 0.7) !important;
            color: #fff !important;
        }
    </style>

    <div class=" py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-10">
                <div class="row g-2">
                    {{-- MENÚ LATERAL IZQUIERDO --}}
                    <div class="col-md-4 col-lg-3">
                        <div class="card border-0 shadow rounded-4">
                            <div class="card-body px-3 py-4">
                                <h4 class="fw-bold text-primary mb-3">Configuraciones</h4>
                                <ul class="nav flex-column">
                                    <li class="nav-item mb-2">
                                        <a href="{{ route('usuarios.perfil', $usuario->id_usuario) }}"
                                            class="nav-link text-dark">
                                            <i class='bx bx-user me-2'></i> Perfil
                                        </a>

                                    </li>
                                    <li class="nav-item mb-2">
                                        <a href="{{ route('usuarios.cuenta.update', $usuario->id_usuario) }}"
                                            class="nav-link text-dark">
                                            <i class='bx bx-lock me-2'></i> Cuenta
                                        </a>
                                    </li>
                                    <li class="nav-item mb-2">
                                        <a href="{{ route('usuarios.notificaciones', $usuario->id_usuario) }}"
                                            class="nav-link text-dark">
                                            <i class='bx bx-bell me-2'></i> Notificaciones
                                        </a>

                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- FORMULARIO PERFIL DERECHO --}}
                    <div class="col-md-8">
                        <div class="row justify-content-center">
                            <div class="col-lg-10 col-xl-10">
                                <div class="card shadow border-0 rounded-4">
                                    @yield('datosUsuario')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const portadaInput = document.getElementById('portadaInput');
            const portadaImg = document.getElementById('portadaImagen');
            const spinnerPortada = document.getElementById('spinnerPortada');

            const perfilInput = document.getElementById('fotoInput');
            const perfilImg = document.getElementById('perfilImagen');
            const spinnerPerfil = document.getElementById('spinnerPerfil');

            function cambiarImagenConPrevisualizacion(input, img, spinner) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    img.style.filter = 'blur(4px)';
                    spinner.classList.remove('d-none');

                    reader.onload = function(e) {
                        setTimeout(() => {
                            img.src = e.target.result;
                            img.style.filter = 'none';
                            spinner.classList.add('d-none');
                        }, 1000);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }

            portadaInput.addEventListener('change', () => {
                cambiarImagenConPrevisualizacion(portadaInput, portadaImg, spinnerPortada);
            });

            perfilInput.addEventListener('change', () => {
                cambiarImagenConPrevisualizacion(perfilInput, perfilImg, spinnerPerfil);
            });
        });
    </script>
@endsection
