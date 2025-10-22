@extends('layouts.plantilla')

@section('titulo', 'Registro Civil - Nacimiento')

@section('contenido')
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="container mt-4 px-3 animate__animated animate__fadeIn">

        {{-- Barra superior de exportación --}}
        <div class="mb-5 d-flex flex-column flex-md-row justify-content-md-between align-items-md-center gap-3">

            {{-- Título --}}
            <div>
                <h3 class="display-5 fw-bold text-primary mb-2 mb-md-0">
                    <span class="bg-gradient-text bg-clip-text"
                        style="background-image: linear-gradient(to right, #4f46e5, #8b5cf6, #ec4899); -webkit-background-clip: text;">
                        👶 Listado de Nacimientos
                    </span>
                </h3>
            </div>

            {{-- Botones --}}
            <div class="d-flex flex-wrap gap-3 align-items-center">

                {{-- Botón Exportar --}}
                <a href="{{ route('nacimiento.exportarPDFMasivo') }}"
                    class="btn btn-primary bg-gradient shadow-sm d-flex align-items-center gap-2 px-4 py-2"
                    style="background-image: linear-gradient(to bottom right, #1e3a8a, #7dd3fc, #0e7490); transition: all 0.3s;">
                    <i class="fas fa-file-export"></i>
                    Exportar
                </a>

                {{-- Botón Nuevo Registro --}}
                <a href="{{ route('nacimiento.create') }}"
                    class="btn btn-primary bg-gradient shadow-sm d-flex align-items-center gap-2 px-4 py-2 fw-semibold"
                    style="background-image: linear-gradient(to bottom right, #1e3a8a, #22d3ee); transition: all 0.3s;">
                    <i class="fas fa-plus"></i>
                    Nuevo Registro
                </a>
            </div>
        </div>

        {{-- Mensaje de información --}}
        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show mt-2" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Buscador --}}
        <div class="d-flex justify-content-end mb-4">
            <form method="GET" class="d-flex gap-2 align-items-center">
                <select name="estado" class="form-select w-auto">
                    <option disabled>-- Estado --</option>
                    <option value="vigente" {{ request('estado') == 'vigente' ? 'selected' : '' }}>Vigente</option>
                    <option value="anulado" {{ request('estado') == 'anulado' ? 'selected' : '' }}>Anulado</option>
                </select>

                <input name="buscar" type="search" placeholder="Buscar por nombre del recién nacido"
                    class="form-control w-auto px-3 py-2 rounded-3 border-2 shadow-sm" value="{{ request('buscar') }}">

                <button type="submit"
                    class="btn btn-success bg-gradient px-4 py-2 shadow-sm d-flex align-items-center gap-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </form>
        </div>

        {{-- Mensaje de éxito --}}
        @if (session('datos'))
            <div id="mensaje"
                class="alert alert-success mb-4 p-3 border-2 rounded-3 shadow-sm animate__animated animate__fadeIn"
                style="background-image: linear-gradient(to bottom right, #dcfce7, #bbf7d0); border-color: #86efac;">
                <strong>¡Éxito!</strong> {{ session('datos') }}
            </div>
        @endif

        {{-- Tabla --}}
        <div class="table-responsive bg-white rounded-3 shadow-lg">
            <table class="table table-hover table-sm text-center align-middle">
                <thead class="text-white text-uppercase"
                    style="background-image: linear-gradient(to right, #1f2937, #111827);">
                    <tr>
                        <th class="px-4 py-3">ID Acta</th>
                        <th class="px-4 py-3">Nombres y Apellidos</th>
                        <th class="px-4 py-3">Fecha Registro</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Folio</th>
                        <th class="px-4 py-3">Alcalde</th>
                        <th class="px-4 py-3">Usuario</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actas as $item)
                        <tr class="hover-effect align-middle bg-white shadow-sm mb-3 rounded-3"
                            style="border-bottom: 16px solid #f3f4f6;">
                            <td class="px-4 py-3 fw-bold text-primary">{{ $item->id_acta_nacimiento }}</td>
                            <td class="px-4 py-3">
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $item->recienNacido->nombre ?? 'N/A' }}</span>
                                    <small class="text-muted">{{ $item->recienNacido->apellido_paterno ?? '' }}
                                        {{ $item->recienNacido->apellido_materno ?? '' }}</small>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $item->fecha_registro }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="badge rounded-pill shadow-sm 
                                    {{ $item->estado == 'vigente' ? 'bg-success' : 'bg-danger' }}"
                                    style="background-image: linear-gradient(to right, {{ $item->estado == 'vigente' ? '#10b981, #059669' : '#ef4444, #b91c1c' }});">
                                    {{ ucfirst($item->estado) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge rounded-pill shadow-sm"
                                    style="background-image: linear-gradient(to right, #e879f9, #c026d3);">
                                    {{ $item->folio->numero_folio ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $item->alcalde->persona->nombres ?? 'N/A' }}</span>
                                    <small class="text-muted">{{ $item->alcalde->persona->apellido_paterno ?? '' }}
                                        {{ $item->alcalde->persona->apellido_materno ?? '' }}</small>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $item->usuario->nombre_usuario ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <div class="d-flex flex-wrap gap-2 justify-content-center">
                                    {{-- Editar --}}
                                    <a href="{{ route('nacimiento.edit', $item->id_acta_nacimiento) }}"
                                        class="btn btn-warning btn-sm d-flex align-items-center gap-2 shadow-sm"
                                        title="Editar">
                                        <i class="fas fa-pen"></i>
                                        <span class="d-none d-md-inline">Editar</span>
                                    </a>
                                    {{-- Exportar PDF --}}
                                    <a href="{{ route('nacimiento.exportarPDF', $item->id_acta_nacimiento) }}"
                                        class="btn btn-danger btn-sm d-flex align-items-center gap-2 shadow-sm"
                                        title="Exportar PDF" target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                        <span class="d-none d-md-inline">PDF</span>
                                    </a>
                                    {{-- Eliminar --}}
                                    @if ($item->estado == 'vigente' && auth()->user()->rol == 'Administrador')
                                        <button
                                            type="button"
                                            class="btn btn-outline-dark btn-sm d-flex align-items-center gap-2 shadow-sm"
                                            onclick="anularActa({{ $item->id_acta_nacimiento }})">
                                            <i class="fas fa-ban"></i>
                                            <span class="d-none d-md-inline">Anular</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-4 text-muted">No se encontraron registros.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <form id="formAnular" method="POST" style="display: none;">
                @csrf
                @method('PUT')
                <input type="hidden" name="motivo" id="motivoInput">
            </form>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: "{{ session('success') }}",
                    confirmButtonText: 'Aceptar'
                });
            @endif
        </script>

        <style>
            .table> :not(:last-child)> :last-child>* {
                border-bottom-color: transparent !important;
            }

            .hover-effect {
                transition: background 0.3s, box-shadow 0.3s;
                margin-bottom: 1rem;
                border-radius: 0.75rem;
            }

            .btn {
                min-width: 100px;
                font-weight: 500;
                transition: all 0.2s;
            }

            .btn i {
                font-size: 1.1rem;
            }

            .btn:hover,
            .btn:focus {
                transform: scale(1.07);
                box-shadow: 0 8px 20px -3px rgba(0, 0, 0, 0.12);
                z-index: 2;
            }

            .btn span {
                font-size: 0.95rem;
            }

            /* Responsive: solo ícono en móvil */
            @media (max-width: 768px) {
                .btn span {
                    display: none !important;
                }

                .btn {
                    min-width: 40px;
                    justify-content: center;
                }
            }
        </style>

        {{-- Paginación --}}
        <div class="mt-4">
            {{ $actas->links() }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        setTimeout(() => {
            let mensaje = document.getElementById('mensaje');
            if (mensaje) mensaje.remove();
        }, 3000);

        document.querySelectorAll('.hover-effect').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.background = 'linear-gradient(to right, #e0e7ff, #fce7f3)';
                this.style.boxShadow = '0 4px 20px 0 rgba(0,0,0,0.07)';
            });
            row.addEventListener('mouseleave', function() {
                this.style.background = '';
                this.style.boxShadow = '';
            });
        });

        function anularActa(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                input: 'text',
                inputLabel: 'Motivo de anulación',
                inputPlaceholder: 'Escribe el motivo...',
                inputValidator: (value) => {
                    if (!value) return 'El motivo es obligatorio';
                },
                showCancelButton: true,
                confirmButtonText: 'Sí, anular',
                cancelButtonText: 'Cancelar',
                icon: 'warning'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('formAnular');
                    form.action = `/nacimiento/anular/${id}`;
                    document.getElementById('motivoInput').value = result.value;
                    form.submit();
                }
            });
        }   
    </script>
@endsection
