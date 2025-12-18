@extends('admin.usuario.perfil')

@section('datosUsuario')
    <div class="card-body p-4">
        {{-- FORMULARIO PRINCIPAL --}}
        <form action="{{ route('usuarios.perfil.update', $usuario->id_usuario) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- ENCABEZADO --}}
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                <div class="me-3">
                    <div class="bg-primary bg-gradient rounded-3 d-flex align-items-center justify-content-center shadow-sm"
                        style="width: 50px; height: 50px;">
                        <i class='bx bx-user-circle text-white fs-4'></i>
                    </div>
                </div>
                <div>
                    <h4 class="mb-1 fw-bold text-primary">Mi Perfil</h4>
                    <p class="text-muted mb-0">Gestiona tu información personal y configuración de cuenta</p>
                </div>
            </div>

            <div class="position-relative shadow-sm"
                style="height: 240px; border-radius: 1rem; margin-bottom: 90px; overflow: hidden; ">

                <div class="position-absolute top-0 start-0 w-100 h-100">
                    <img id="portadaImagen"
                        src="{{ isset($usuario) && $usuario->portada ? asset('storage/' . $usuario->portada) : asset('/img/Foto-portada.jpg') }}"
                        alt="Portada" class="w-100 h-100"
                        style="object-fit: cover; object-position: center; transition: all 0.3s; image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;">

                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-10"></div>
                </div>

                <div class="position-absolute bottom-0 end-0 m-3">
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle shadow border-0 fw-medium" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false"
                            style="backdrop-filter: blur(10px); background-color: rgba(255,255,255,0.9);">
                            <i class='bx bx-edit-alt me-1'></i> Editar
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="border-radius: 0.75rem;">
                            <li>
                                <label class="dropdown-item py-2 fw-medium" style="cursor: pointer;">
                                    <i class='bx bx-image me-2 text-primary'></i>Cambiar portada
                                    <input type="file" name="portada" id="portadaInput" class="d-none" accept="image/*">
                                </label>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <label class="dropdown-item py-2 fw-medium" style="cursor: pointer;">
                                    <i class='bx bx-user me-2 text-success'></i>Cambiar foto de perfil
                                    <input type="file" name="foto" id="fotoInput" class="d-none" accept="image/*">
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="d-flex justify-content-center" style="margin-top: -130px;">
                <div class="position-relative" style="width: 140px; height: 140px;">
                    <img id="perfilImagen"
                        src="{{ $usuario->foto ? asset('storage/' . $usuario->foto) : asset('/img/Logo_Imagen_FA.png') }}"
                        alt="Foto de perfil" class="rounded-circle border border-4 border-white shadow-lg w-100 h-100"
                        style="
                 object-fit: cover;
                 background-color: rgb(13, 110, 253);
                 transition: all 0.3s;
                 z-index: 2;
                 position: relative;
             ">
                    <div class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-3 border-white"
                        style="width: 28px; height: 28px; z-index: 4"></div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div id="portadaStatus" class="d-none">
                        <div class="alert alert-info border-0 shadow-sm py-3"
                            style="border-radius: 0.75rem; background-color: rgba(13, 202, 240, 0.1);">
                            <div class="d-flex align-items-center">
                                <i class='bx bx-image me-2 text-info fs-5'></i>
                                <div class="flex-grow-1">
                                    <small class="text-muted d-block">Portada seleccionada:</small>
                                    <span id="portadaNombre" class="fw-medium"></span>
                                </div>
                                <button type="button" class="btn-close btn-sm" onclick="limpiarPortada()"></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="fotoStatus" class="d-none">
                        <div class="alert alert-success border-0 shadow-sm py-3"
                            style="border-radius: 0.75rem; background-color: rgba(25, 135, 84, 0.1);">
                            <div class="d-flex align-items-center">
                                <i class='bx bx-user me-2 text-success fs-5'></i>
                                <div class="flex-grow-1">
                                    <small class="text-muted d-block">Foto de perfil seleccionada:</small>
                                    <span id="fotoNombre" class="fw-medium"></span>
                                </div>
                                <button type="button" class="btn-close btn-sm" onclick="limpiarFoto()"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FORMULARIO DE DATOS PERSONALES --}}
            <div class="card border-0 shadow-sm" style="border-radius: 1rem;">
                <div class="card-header bg-light border-0 py-3" style="border-radius: 1rem 1rem 0 0;">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class='bx bx-user-detail me-2 text-primary'></i>
                        Información Personal
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">
                                <i class='bx bx-user me-2 text-primary'></i>Nombre de Usuario
                            </label>
                            <input type="text" name="nombre_usuario"
                                class="form-control form-control-lg shadow-sm border-0"
                                style="background-color: #f8f9fa; border-radius: 0.75rem;"
                                value="{{ old('nombre_usuario', $usuario->nombre_usuario) }}">
                            @error('nombre_usuario')
                                <small class="text-danger fw-medium">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6"></div>


                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">
                                <i class='bx bx-envelope me-2 text-primary'></i>Email Mi Acta
                            </label>
                            <input type="email" name="email_mi_acta"
                                class="form-control form-control-lg shadow-sm border-0"
                                style="background-color: #f8f9fa; border-radius: 0.75rem;"
                                value="{{ old('email_mi_acta', $usuario->email_mi_acta) }}">
                            @error('email_mi_acta')
                                <small class="text-danger fw-medium">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">
                                <i class='bx bx-envelope-open me-2 text-info'></i>Email de Respaldo
                            </label>
                            <input type="email" name="email_respaldo"
                                class="form-control form-control-lg shadow-sm border-0"
                                style="background-color: #f8f9fa; border-radius: 0.75rem;"
                                value="{{ old('email_respaldo', $usuario->email_respaldo) }}">
                            @error('email_respaldo')
                                <small class="text-danger fw-medium">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">
                                <i class='bx bx-lock me-2 text-warning'></i>Contraseña
                            </label>
                            <input type="password" name="contrasena"
                                class="form-control form-control-lg shadow-sm border-0"
                                style="background-color: #f8f9fa; border-radius: 0.75rem;" placeholder="Nueva contraseña">
                            <small class="text-muted fst-italic">Dejar en blanco si no desea cambiarla.</small>
                            @error('contrasena')
                                <small class="text-danger fw-medium">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">
                                <i class='bx bx-shield me-2 text-success'></i>Rol asignado
                            </label>
                            <select name="rol" class="form-select form-control-lg shadow-sm border-0"
                                style="background-color: #f8f9fa; border-radius: 0.75rem;">
                                <option value="">Seleccione un rol</option>
                                <option value="administrador" {{ old('rol', $usuario->getRoleNames()->first()) == 'administrador' ? 'selected' : '' }}>Administrador</option>
                                <option value="cocinero" {{ old('rol', $usuario->getRoleNames()->first()) == 'cocinero' ? 'selected' : '' }}>Cocinero</option>
                                <option value="almacenero" {{ old('rol', $usuario->getRoleNames()->first()) == 'almacenero' ? 'selected' : '' }}>Almacenero</option>
                                <option value="cajero" {{ old('rol', $usuario->getRoleNames()->first()) == 'cajero' ? 'selected' : '' }}>Cajero</option>
                                <option value="mesero" {{ old('rol', $usuario->getRoleNames()->first()) == 'mesero' ? 'selected' : '' }}>Mesero</option>
                                <option value="registrador" {{ old('rol', $usuario->getRoleNames()->first()) == 'registrador' ? 'selected' : '' }}>Registrador</option>
                            </select>
                            @error('rol')
                                <small class="text-danger fw-medium">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">
                                <i class='bx bx-check-circle me-2 text-success'></i>Estado
                            </label>
                            <input type="text" class="form-control form-control-lg shadow-sm border-0"
                                style="background-color: #e9ecef; border-radius: 0.75rem;"
                                value="{{ $usuario->estado == 1 ? 'Activo' : 'Inactivo' }}" readonly>
                        </div>

                        @error('foto')
                            <div class="col-12">
                                <div class="alert alert-danger border-0 shadow-sm py-3" style="border-radius: 0.75rem;">
                                    <i class='bx bx-error-circle me-2 fs-5'></i>
                                    <strong>Error en foto de perfil:</strong> {{ $message }}
                                </div>
                            </div>
                        @enderror

                        @error('portada')
                            <div class="col-12">
                                <div class="alert alert-danger border-0 shadow-sm py-3" style="border-radius: 0.75rem;">
                                    <i class='bx bx-error-circle me-2 fs-5'></i>
                                    <strong>Error en portada:</strong> {{ $message }}
                                </div>
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <button type="submit" class="btn btn-primary btn-lg px-5 py-3 shadow-lg border-0 fw-bold"
                    style="border-radius: 2rem; background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                    <i class="ri-save-2-line me-2"></i>Actualizar Perfil
                </button>
            </div>
        </form>
    </div>

    {{-- Script mejorado para previsualización de imágenes --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fotoInput = document.getElementById('fotoInput');
            const portadaInput = document.getElementById('portadaInput');
            const perfilImagen = document.getElementById('perfilImagen');
            const portadaImagen = document.getElementById('portadaImagen');
            const fotoStatus = document.getElementById('fotoStatus');
            const portadaStatus = document.getElementById('portadaStatus');
            const fotoNombre = document.getElementById('fotoNombre');
            const portadaNombre = document.getElementById('portadaNombre');

            // Función para validar imagen
            function validarImagen(archivo, maxSize) {
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

                if (!validTypes.includes(archivo.type)) {
                    mostrarAlerta('Por favor seleccione una imagen válida (JPG, JPEG, PNG, WebP).', 'error');
                    return false;
                }

                if (archivo.size > maxSize * 1024) {
                    mostrarAlerta(`La imagen es demasiado grande. El tamaño máximo es ${maxSize / 1024}MB.`,
                        'error');
                    return false;
                }

                return true;
            }

            // Función para mostrar alertas elegantes
            function mostrarAlerta(mensaje, tipo) {
                const alertClass = tipo === 'error' ? 'alert-danger' : 'alert-success';
                const icon = tipo === 'error' ? 'bx-error-circle' : 'bx-check-circle';

                const alertHtml = `
                    <div class="alert ${alertClass} border-0 shadow-sm alert-dismissible fade show" 
                         style="border-radius: 0.75rem; position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 350px;">
                        <i class='bx ${icon} me-2'></i>
                        <strong>${mensaje}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;

                document.body.insertAdjacentHTML('beforeend', alertHtml);

                // Auto-remover después de 5 segundos
                setTimeout(() => {
                    const alert = document.querySelector('.alert');
                    if (alert) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            }

            // Función para crear imagen con mejor calidad
            function crearImagenOptimizada(archivo, callback, maxWidth = 800) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');

                        // Calcular dimensiones manteniendo la proporción
                        let {
                            width,
                            height
                        } = img;
                        if (width > maxWidth) {
                            height = (height * maxWidth) / width;
                            width = maxWidth;
                        }

                        canvas.width = width;
                        canvas.height = height;

                        // Configurar para mejor calidad
                        ctx.imageSmoothingEnabled = true;
                        ctx.imageSmoothingQuality = 'high';

                        // Dibujar imagen
                        ctx.drawImage(img, 0, 0, width, height);

                        // Convertir a URL con alta calidad
                        const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
                        callback(dataUrl);
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(archivo);
            }

            // Manejar cambio en foto de perfil
            fotoInput.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const archivo = e.target.files[0];

                    // Validar imagen (3MB máximo)
                    if (!validarImagen(archivo, 3072)) {
                        e.target.value = '';
                        return;
                    }

                    // Crear imagen optimizada y previsualizar
                    crearImagenOptimizada(archivo, function(dataUrl) {
                        perfilImagen.src = dataUrl;
                        perfilImagen.style.transform = 'scale(1.05)';
                        setTimeout(() => {
                            perfilImagen.style.transform = 'scale(1)';
                        }, 200);
                    }, 400);

                    // Mostrar estado
                    fotoNombre.textContent = archivo.name;
                    fotoStatus.classList.remove('d-none');
                    fotoStatus.style.animation = 'fadeIn 0.3s ease-in';

                    mostrarAlerta('Foto de perfil cargada correctamente', 'success');
                }
            });

            // Manejar cambio en portada
            portadaInput.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const archivo = e.target.files[0];

                    // Validar imagen (5MB máximo)
                    if (!validarImagen(archivo, 5120)) {
                        e.target.value = '';
                        return;
                    }

                    // Crear imagen optimizada y previsualizar
                    crearImagenOptimizada(archivo, function(dataUrl) {
                        portadaImagen.src = dataUrl;
                        portadaImagen.style.transform = 'scale(1.02)';
                        setTimeout(() => {
                            portadaImagen.style.transform = 'scale(1)';
                        }, 300);
                    }, 1200);

                    // Mostrar estado
                    portadaNombre.textContent = archivo.name;
                    portadaStatus.classList.remove('d-none');
                    portadaStatus.style.animation = 'fadeIn 0.3s ease-in';

                    mostrarAlerta('Portada cargada correctamente', 'success');
                }
            });

            // Funciones globales para limpiar archivos
            window.limpiarFoto = function() {
                fotoInput.value = '';
                perfilImagen.src =
                    '{{ $usuario->foto ? asset('storage/' . $usuario->foto) : asset('/img/Logo_Imagen_FA.png') }}';
                fotoStatus.classList.add('d-none');
                perfilImagen.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    perfilImagen.style.transform = 'scale(1)';
                }, 200);
            };

            window.limpiarPortada = function() {
                portadaInput.value = '';
                portadaImagen.src =
                    '{{ isset($usuario) && $usuario->portada ? asset('storage/' . $usuario->portada) : asset('/img/Foto-portada.jpg') }}';
                portadaStatus.classList.add('d-none');
                portadaImagen.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    portadaImagen.style.transform = 'scale(1)';
                }, 300);
            };
        });

        // Agregar estilos CSS para animaciones
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            .form-control:focus {
                box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15) !important;
                border-color: transparent !important;
            }
            
            .btn:hover {
                transform: translateY(-2px);
            }
            
            .btn {
                transition: all 0.3s ease;
            }
            
            img {
                transition: transform 0.3s ease;
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection
