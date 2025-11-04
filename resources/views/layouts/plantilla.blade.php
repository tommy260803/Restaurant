<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESTAURANTE</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/darkmode.css">
    @yield('styles')
</head>

<body>
    <!--Sidebar-->
    @php
        $usuario = auth()->user();
    @endphp

    <div class="custom-sidebar collapsed" id="customSidebar">
        <div class="custom-sidebar-header">
            <div class="container-logo">
                <a href="{{ route('home') }}" class="custom-header-logo">
                    <img src="/img/resta.png" class="custom-header-logo" alt="Logo" />
                </a>
            </div>
        </div>

        <nav class="custom-sidebar-nav">
            <ul class="custom-nav-list custom-primary-nav">

                <!-- INICIO -->
                <li class="custom-nav-item">
                    <a href="{{ route('home') }}" class="custom-nav-link">
                        <i class='bx bx-home-alt-2' style="font-size: 30px;"></i>
                        <span class="custom-nav-label">Inicio</span>
                    </a>
                    <ul class="custom-dropdown-menu">
                        <li class="custom-nav-item">
                            <a class="custom-nav-link custom-dropdown-title">Inicio</a>
                        </li>
                    </ul>
                </li>

                <!-- MESAS -->
                <li class="custom-nav-item">
                    <a href="#" class="custom-nav-link">
                        <i class='bx bx-table' style="font-size: 30px;"></i>
                        <span class="custom-nav-label">Mesas</span>
                    </a>
                    <ul class="custom-dropdown-menu">
                        <li class="custom-nav-item">
                            <a class="custom-nav-link custom-dropdown-title">Mesas</a>
                        </li>
                    </ul>
                </li>

                <!-- CATEGORÍAS -->
                <li class="custom-nav-item">
                    <a href="{{ route('mantenedor.categorias.index') }}" class="custom-nav-link">
                        <i class='bx bx-category' style="font-size: 30px;"></i>
                        <span class="custom-nav-label">Categorías</span>
                    </a>
                    <ul class="custom-dropdown-menu">
                        <li class="custom-nav-item">
                            <a class="custom-nav-link custom-dropdown-title">Categorias</a>
                        </li>
                    </ul>
                </li>

                <!-- PLATOS -->
                <li class="custom-nav-item">
                    <a href="{{ route('mantenedor.platos.index') }}" class="custom-nav-link">
                        <i class='bx bx-restaurant' style="font-size: 30px;"></i>
                        <span class="custom-nav-label">Platos</span>
                    </a>
                    <ul class="custom-dropdown-menu">
                        <li class="custom-nav-item">
                            <a class="custom-nav-link custom-dropdown-title">platos</a>
                        </li>
                    </ul>
                </li>

                <!-- INVENTARIO -->
                <li class="custom-nav-item custom-dropdown-container">
                    <a href="#" class="custom-nav-link custom-dropdown-toggle primario">
                        <i class='bx bx-package' style="font-size: 30px;"></i>
                        <span class="custom-nav-label">Inventario</span>
                        <i class='bx bx-chevron-down custom-dropdown-icon'></i>
                    </a>
                    <ul class="custom-dropdown-menu">
                        <li class="custom-nav-item">
                            <a class="custom-nav-link custom-dropdown-title">Inventario</a>
                        </li>
                        <hr>
                        <li class="custom-nav-item">
                            <a href="{{ route('ingredientes.index') }}" class="custom-nav-link">Ingredientes</a>
                        </li>
                        <li class="custom-nav-item">
                            <a href="{{ route('almacenes.index') }}" class="custom-nav-link">Almacenes</a>
                        </li>
                        <li class="custom-nav-item">
                            <a href="{{ route('movimientos-inventario.index') }}" class="custom-nav-link">Movimientos</a>
                        </li>
                    </ul>
                </li>

                <!-- VENTAS Y PEDIDOS -->
                <li class="custom-nav-item custom-dropdown-container">
                    <a href="#" class="custom-nav-link custom-dropdown-toggle primario">
                        <i class='bx bx-cart' style="font-size: 30px;"></i>
                        <span class="custom-nav-label">Ventas y Pedidos</span>
                        <i class='bx bx-chevron-down custom-dropdown-icon'></i>
                    </a>
                    <ul class="custom-dropdown-menu">
                        <li class="custom-nav-item custom-dropdown-container">
                            <a href="#" class="custom-nav-link custom-dropdown-toggle secundario">
                                <span>Pedidos</span>
                                <i class='bx bx-chevron-down custom-dropdown-icon2'></i>
                            </a>
                            <ul class="custom-dropdown-menu">
                                <li class="custom-nav-item"><a href="#" class="custom-nav-link">En curso</a></li>
                                <li class="custom-nav-item"><a href="#" class="custom-nav-link">Entregados</a></li>
                                <li class="custom-nav-item"><a href="#" class="custom-nav-link">Cancelados</a></li>
                            </ul>
                        </li>
                        <li class="custom-nav-item custom-dropdown-container">
                            <a href="#" class="custom-nav-link custom-dropdown-toggle secundario">
                                <span>Ventas</span>
                                <i class='bx bx-chevron-down custom-dropdown-icon2'></i>
                            </a>
                            <ul class="custom-dropdown-menu">
                                <li class="custom-nav-item"><a href="#" class="custom-nav-link">Histórico</a></li>
                                <li class="custom-nav-item"><a href="#" class="custom-nav-link">Facturación</a></li>
                                <li class="custom-nav-item"><a href="#" class="custom-nav-link">Reportes</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <!-- COMPRAS -->
                <li class="custom-nav-item custom-dropdown-container">
                    <a href="#" class="custom-nav-link custom-dropdown-toggle primario">
                        <i class='bx bx-shopping-bag' style="font-size: 30px;"></i>
                        <span class="custom-nav-label">Compras</span>
                        <i class='bx bx-chevron-down custom-dropdown-icon'></i>
                    </a>
                    <ul class="custom-dropdown-menu">
                        <li class="custom-nav-item">
                            <a class="custom-nav-link custom-dropdown-title">Compras</a>
                        </li>
                        <hr>
                        <li class="custom-nav-item">
                            <a href="{{ route('compras.index') }}" class="custom-nav-link">Lista de Compras</a>
                        </li>
                        <li class="custom-nav-item">
                            <a href="{{ route('compras.create') }}" class="custom-nav-link">Nueva Compra</a>
                        </li>
                    </ul>
                </li>

                <!-- CLIENTES -->
                <li class="custom-nav-item">
                    <a href="#" class="custom-nav-link">
                        <i class='bx bx-user-circle' style="font-size: 30px;"></i>
                        <span class="custom-nav-label">Clientes</span>
                    </a>
                    <ul class="custom-dropdown-menu">
                        <li class="custom-nav-item">
                            <a class="custom-nav-link custom-dropdown-title">Clientes</a>
                        </li>
                    </ul>
                </li>

                <!-- PROVEEDORES -->
                <li class="custom-nav-item">
                    <a href="{{ route('proveedor.index') }}" class="custom-nav-link">
                        <i class='bx bx-package' style="font-size: 30px;"></i> <span class="custom-nav-label">Proveedores</span>
                    </a>
                    <ul class="custom-dropdown-menu">
                        <li class="custom-nav-item">
                            <a class="custom-nav-link custom-dropdown-title">Proveedores</a>
                        </li>
                    </ul>
                </li>
                <!-- ADMINISTRACIÓN -->
                <li class="custom-nav-item custom-dropdown-container">
                    <a href="#" class="custom-nav-link custom-dropdown-toggle primario">
                        <i class='bx bx-user' style="font-size: 30px;"></i>
                        <span class="custom-nav-label">Administración</span>
                        <i class='bx bx-chevron-down custom-dropdown-icon'></i>
                    </a>

                    <!--Primer menú desplegable con hover-->
                    <ul class="custom-dropdown-menu">
                        <li class="custom-nav-item"><a class="custom-nav-link custom-dropdown-title">Administración</a>
                        </li>
                        <hr>
                        <!-- USUARIOS -->
                        @role('administrador')
                            <li class="custom-nav-item custom-dropdown-container">
                                <a href="#" class="custom-nav-link custom-dropdown-toggle secundario">
                                    <span>Usuarios</span>
                                    <i class='bx bx-chevron-down custom-dropdown-icon2'></i>
                                </a>

                                <!--Segundo menu desplegable con hover-->
                                <ul class="custom-dropdown-menu">
                                    <li class="custom-nav-item">
                                        <a class="custom-nav-link custom-dropdown-title">Usuarios</a>
                                    </li>
                                    <hr>
                                    <li class="custom-nav-item">
                                        <a href="{{ route('usuarios.index') }}" class="custom-nav-link">Lista de
                                            Usuarios</a>
                                    </li>
                                </ul>
                            </li>
                        @endrole

                        @role('administrador')
                            <li class="custom-nav-item custom-dropdown-container">
                                <a href="#" class="custom-nav-link custom-dropdown-toggle secundario">
                                    <span>Personas</span>
                                    <i class='bx bx-chevron-down custom-dropdown-icon2'></i>
                                </a>

                                <!--Segundo menu desplegable con hover-->
                                <ul class="custom-dropdown-menu">
                                    <li class="custom-nav-item">
                                        <a class="custom-nav-link custom-dropdown-title">Personas</a>
                                    </li>
                                    <hr>
                                    <li class="custom-nav-item">
                                        <a href="{{ route('persona.create') }}" class="custom-nav-link">Registrar
                                            Persona</a>
                                    </li>
                                    <li class="custom-nav-item">
                                        <a href="{{ route('persona.index') }}" class="custom-nav-link">Listar Personas</a>
                                    </li>
                                </ul>
                            </li>
                        @endrole
                        @role('administrador')
                            <li class="custom-nav-item custom-dropdown-container">
                                <a href="#" class="custom-nav-link custom-dropdown-toggle secundario">
                                    <span>Alcaldes</span>
                                    <i class='bx bx-chevron-down custom-dropdown-icon2'></i>
                                </a>

                                <!--Segundo menu desplegable con hover-->
                                <ul class="custom-dropdown-menu">
                                    <li class="custom-nav-item">
                                        <a class="custom-nav-link custom-dropdown-title">Alcaldes</a>
                                    </li>
                                    <hr>
                                    <li class="custom-nav-item">
                                        <a href="{{ route('alcalde.index') }}" class="custom-nav-link">Listar
                                            Alcaldes</a>
                                    </li>
                                </ul>
                            </li>
                        @endrole 
                    </ul>
                </li>

                <!-- ROLES Y PERMISOS -->
                @role('administrador')
                    <li class="custom-nav-item custom-dropdown-container">
                        <a href="#" class="custom-nav-link custom-dropdown-toggle primario">
                            <i class='bx bx-user-minus' style="font-size: 35px;"></i>
                            <span class="custom-nav-label">Roles y Permisos</span>
                            <i class='bx bx-chevron-down custom-dropdown-icon'></i>
                        </a>

                        <!--Primer menú desplegable con hover-->
                        <ul class="custom-dropdown-menu">
                            <li class="custom-nav-item"><a class="custom-nav-link custom-dropdown-title">Roles y
                                    Permisos</a>
                            </li>
                            <hr>
                            <li class="custom-nav-item"><a href="{{ route('roles.index') }}"
                                    class="custom-nav-link">Roles</a></li>
                            <li class="custom-nav-item"><a href="{{ route('permisos.index') }}"
                                    class="custom-nav-link">Permisos</a></li>
                        </ul>
                    </li>


                    <!-- PAGOS -->
                    <li class="custom-nav-item custom-dropdown-container">
                        <a href="#" class="custom-nav-link custom-dropdown-toggle primario">
                            <i class='bx bx-credit-card' style="font-size: 30px;"></i>
                            <span class="custom-nav-label">Pagos</span>
                            <i class='bx bx-chevron-down custom-dropdown-icon'></i>
                        </a>
                        <ul class="custom-dropdown-menu">
                            <li class="custom-nav-item"><a class="custom-nav-link custom-dropdown-title">Pagos</a></li>
                            <hr>
                            <li class="custom-nav-item"><a href="{{ route('pagos.index') }}"
                                    class="custom-nav-link">Lista de Pagos</a></li>
                            <li class="custom-nav-item"><a href="{{ route('tarifas.index') }}"
                                    class="custom-nav-link">Tarifas</a></li>
                            <li class="custom-nav-item"><a href="{{ route('pagos.reportes') }}"
                                    class="custom-nav-link">Reportes Financieros</a></li>
                        </ul>
                    </li>

                    <!-- NOTIFICACIONES -->
                    <li class="custom-nav-item custom-dropdown-container">
                        {{-- <a href="{{ route('notificaciones.index') }}"
                        class="custom-nav-link custom-dropdown-toggle primario">
                        <i class="ri-notification-3-line"></i>
                        Notificaciones
                        <span class="badge bg-danger ms-1" id="notificaciones-count">0</span>
                    </a> --}}
                    </li>
                @endrole
            </ul>

            <!-- ACERCA DE -->
            <ul class="custom-nav-list custom-secondary-nav">
                <li class="custom-nav-item">
                    <a href="#" class="custom-nav-link">
                        <i class='bx bx-info-circle' style="font-size: 30px;"></i>
                        <span class="custom-nav-label">Acerca de</span>
                    </a>
                    <ul class="custom-dropdown-menu">
                        <li class="custom-nav-item"><a class="custom-nav-link custom-dropdown-title">Acerca de</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="custom-main-content collapsed" id="mainContent">
        <nav class="navbar navbar-custom navbar-expand-lg">
            <div class="container-fluid">
                <div class="d-flex align-items-center custom-sidebar-toggler">
                    <svg class="ham hamRotate ham1 hamburger-click" viewBox="0 0 100 100" width="50"
                        onclick="this.classList.toggle('active')" id="hamburger-click">
                        <path class="line top"
                            d="m 30,33 h 40 c 0,0 9.044436,-0.654587 9.044436,-8.508902 0,-7.854315 -8.024349,-11.958003 -14.89975,-10.85914 -6.875401,1.098863 -13.637059,4.171617 -13.637059,16.368042 v 40" />
                        <path class="line middle" d="m 30,50 h 40" />
                        <path class="line bottom"
                            d="m 30,67 h 40 c 12.796276,0 15.357889,-11.717785 15.357889,-26.851538 0,-15.133752 -4.786586,-27.274118 -16.667516,-27.274118 -11.88093,0 -18.499247,6.994427 -18.435284,17.125656 l 0.252538,40" />
                    </svg>
                    <span class="navbar-brand text-white fw-bold">UMAMI</span>
                </div>

                <div class="d-flex align-items-center">
                    <button class="btn text-white position-relative me-3" id="themeToggle">
                        <i class='bx bx-moon fs-3'></i>
                    </button>

                    <div class="dropdown">
                        <button class="btn text-white dropdown-toggle d-flex align-items-center" type="button"
                            id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                style="width: 40px; height: 40px;">
                                <img src="{{ isset($usuario) && $usuario->foto ? asset('storage/' . $usuario->foto) . '?v=' . time() : asset('/img/resta.png') }}"
                                    alt="Foto de perfil" class="rounded-circle border border-white shadow w-100 h-100"
                                    style="object-fit: cover; background-color: rgb(13, 110, 253); transition: filter 0.3s;">
                            </div>

                            @if ($usuario)
                                <span class="d-none d-md-inline">{{ $usuario->nombre_usuario }}</span>
                            @else
                                <span class="text-danger">Usuario no disponible</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <div class="d-flex align-items-center gap-3 px-3 py-2">
                                    <div class="bg-light border rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 48px; height: 48px;">
                                        <img src="{{ $usuario->foto ? asset('storage/' . $usuario->foto) . '?v=' . time() : asset('/img/resta.png') }}"
                                            alt="Foto de perfil"
                                            class="rounded-circle border border-white w-100 h-100"
                                            style="object-fit: cover; transition: filter 0.3s;">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">{{ $usuario->nombre_usuario }}</div>
                                        <div class="text-muted small">{{ $usuario->email_mi_acta }}</div>
                                        <div class="mt-1">
                                            @if($usuario->roles->isNotEmpty())
                                                @foreach($usuario->roles as $role)
                                                    <span class="badge 
                                                        @if($role->name == 'administrador') bg-danger
                                                        @elseif($role->name == 'cocinero') bg-warning
                                                        @elseif($role->name == 'almacenero') bg-info
                                                        @elseif($role->name == 'cajero') bg-success
                                                        @else bg-secondary
                                                        @endif">
                                                        {{ ucfirst($role->name) }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="badge bg-secondary">Sin rol</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <a class="dropdown-item"
                                href="{{ route('usuarios.perfil.update', $usuario->id_usuario) }}">
                                <i class='bx bx-user me-2'></i> Mi Perfil
                            </a>
                            </li>
                            <li><a class="dropdown-item" href="#"><i class='bx bx-cog me-2'></i>
                                    Configuración</a>
                            </li>
                            <li><a class="dropdown-item" href="#"><i class='bx bx-help-circle me-2'></i>
                                    Ayuda
                                    y
                                    Soporte</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" onclick="confirmLogout(event)">
                                    <i class='bx bx-log-out me-2'></i> Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <div style="padding: 2rem; display: flex; justify-content: center; justify-items: center;">
            @yield('contenido')
        </div>
    </div>

    <!-- Bootstrap JS y dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js para gráficos -->
    <script src="/js/sidebar.js"></script>
    <script src="/js/darkmode.js"></script>

    <script>
        function confirmLogout(event) {
            event.preventDefault();

            Swal.fire({
                title: '¿Deseas cerrar sesión?',
                text: "Se cerrará tu sesión activa.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, cerrar sesión',
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'rounded-4 shadow',
                    confirmButton: 'btn btn-danger rounded-pill px-4',
                    cancelButton: 'btn btn-secondary rounded-pill px-4'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
    @yield('js')
    <style>
        .swal2-actions {
            gap: 0.75rem !important;
            /* o prueba 1rem para más separación */
        }
    </style>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    @stack('scripts')
</body>


</html>
